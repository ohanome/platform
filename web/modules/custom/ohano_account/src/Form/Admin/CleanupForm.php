<?php

namespace Drupal\ohano_account\Form\Admin;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\ohano_account\Entity\Account;
use Drupal\ohano_account\Entity\AccountActivation;
use Drupal\ohano_account\Entity\AccountVerification;
use Drupal\ohano_core\Settings;
use Drupal\user\Entity\User;

/**
 * Provides a form for cleaning up the account system.
 */
class CleanupForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'ohano_account_admin_account_create';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $form = [];

    $countUsers = count(\Drupal::entityQuery('user')->execute());
    $countAccounts = count(\Drupal::entityQuery(Account::ENTITY_ID)->execute());
    $countVerifications = count(\Drupal::entityQuery(AccountVerification::ENTITY_ID)->execute());
    $countActivations = count(\Drupal::entityQuery(AccountActivation::ENTITY_ID)->execute());
    $count = $countUsers - $countAccounts;

    $form['found_users'] = [
      '#type' => 'textfield',
      '#attributes' => [
        'disabled' => 'disabled',
      ],
      '#title' => $this->t('Found users'),
      '#value' => $countUsers,
    ];

    $form['found_accounts'] = [
      '#type' => 'textfield',
      '#attributes' => [
        'disabled' => 'disabled',
      ],
      '#title' => $this->t('Found account entities'),
      '#value' => $countAccounts,
    ];

    $form['found_activations'] = [
      '#type' => 'textfield',
      '#attributes' => [
        'disabled' => 'disabled',
      ],
      '#title' => $this->t('Found activations'),
      '#value' => $countActivations,
    ];

    $form['found_verifications'] = [
      '#type' => 'textfield',
      '#attributes' => [
        'disabled' => 'disabled',
      ],
      '#title' => $this->t('Found verifications'),
      '#value' => $countVerifications,
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Cleanup'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $users = \Drupal::entityQuery('user')->execute();
    $accounts = \Drupal::entityQuery(Account::ENTITY_ID)->execute();
    $activations = \Drupal::entityQuery(AccountActivation::ENTITY_ID)->execute();
    $verifications = \Drupal::entityQuery(AccountVerification::ENTITY_ID)->execute();

    // Counter.
    $deletedAccounts = 0;
    $deletedActivations = 0;
    $updatedActivations = 0;
    $deletedVerifications = 0;
    $createdAccounts = 0;

    // Delete all accounts, activations and verifications where the user
    // entities don't exist anymore.
    foreach ($accounts as $accountId) {
      $account = Account::load($accountId);
      $userId = $account->get('user')->target_id;
      if (!in_array($userId, $users)) {
        $account->delete();
        $deletedAccounts++;
      }
    }

    foreach ($activations as $activationId) {
      $activation = AccountActivation::load($activationId);
      if (empty(user_load_by_name($activation->getUsername()))) {
        $activation->delete();
        $deletedActivations++;
        continue;
      }

      $now = new DrupalDateTime();
      $now = (int) $now->format('U');
      $activationCreated = $activation->getCreated();
      if (!$activation->isValid() && ($now - $activationCreated) >= Settings::ACTIVATION_TTL) {
        $activation->delete();
        $deletedActivations++;
        continue;
      }

      if ($activation->isValid() && $activation->getCode() != Settings::ACTIVATION_INVALIDATION_CODE) {
        $activation->setCode(Settings::ACTIVATION_INVALIDATION_CODE);
        $activation->save();
        $updatedActivations++;
      }
    }

    foreach ($verifications as $verificationId) {
      $verification = AccountVerification::load($verificationId);
      $userId = $verification->get('user')->target_id;
      if (!in_array($userId, $users)) {
        $verification->delete();
        $deletedVerifications++;
      }
    }

    foreach ($users as $userId) {
      $accountId = \Drupal::entityQuery(Account::ENTITY_ID)
        ->condition('user', $userId)
        ->execute();

      if (count($accountId) > 1) {
        $highestAccountId = 0;
        foreach ($accountId as $aid) {
          if ($aid > $highestAccountId) {
            $highestAccountId = $aid;
          }
        }

        foreach ($accountId as $aid) {
          if ($aid != $highestAccountId) {
            Account::load($aid)->delete();
          }
        }
      }
      elseif (count($accountId) < 1 && $userId != 0) {
        Account::create()
          ->setUser(User::load($userId))
          ->setBits(Settings::STARTING_BITS)
          ->save();
        $createdAccounts++;
      }
    }

    $this->messenger()->addMessage($this->t("Deleted @count account entities because the user entities could not be found.", ['@count' => $deletedAccounts]));
    $this->messenger()->addMessage($this->t("Deleted @count activation entities for non-existing users.", ['@count' => $deletedActivations]));
    $this->messenger()->addMessage($this->t("Deleted @count verification entities for non-existing users.", ['@count' => $deletedVerifications]));
    $this->messenger()->addMessage($this->t("Updated @count activation entities which were successful but not invalidated.", ['@count' => $updatedActivations]));
    $this->messenger()->addMessage($this->t("Created @count account entities for users where accounts didn't exist.", ['@count' => $createdAccounts]));
  }

}
