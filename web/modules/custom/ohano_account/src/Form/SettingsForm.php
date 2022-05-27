<?php

namespace Drupal\ohano_account\Form;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\ohano_account\Entity\Account;
use Drupal\ohano_account\Option\ColorMode;
use Drupal\ohano_account\Option\ColorShade;
use Drupal\ohano_account\Option\FontSize;
use Drupal\ohano_notification\Option\NotificationChannel;
use Drupal\ohano_core\Form\FormTrait;
use Drupal\ohano_notification\Option\NotificationType;
use Drupal\ohano_profile\Entity\UserProfile;

class SettingsForm extends FormBase {

  use FormTrait;

  public function getFormId() {
    return 'ohano_account__settings';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = [];

    $currentUser = \Drupal::currentUser();
    $account = Account::forActive();

    if (empty($account)) {
      $this->messenger()->addError($this->t('Could not load account information'));
      return [];
    }

    $profiles = UserProfile::loadMultipleByUser(\Drupal::currentUser());
    $options = [];
    foreach ($profiles as $profile) {
      $options[$profile->getId()] = $profile->getProfileName();
    }

    $form['account_info'] = $this->buildDefaultContainer($this->t('Account info'));
    $form['account_info']['username'] = $this->buildTextField($this->t('Username'), $currentUser->getAccountName(), TRUE);
    $form['account_info']['email'] = $this->buildEmailField($this->t('Email address'), $currentUser->getEmail(), TRUE);
    $form['account_info']['reset_password'] = [
      '#type' => 'password',
      '#title' => $this->t('Reset password'),
      '#description' => $this->t('Only when both password fields are filled, the password will be reset.')
    ];
    $form['account_info']['reset_password_confirm'] = [
      '#type' => 'password',
      '#title' => $this->t('Confirm new password'),
    ];

    $activeTier = $account->get('subscription_tier')->value;
    $activeUntil = $account->get('subscription_active')->value;
    $activeUntil = DrupalDateTime::createFromFormat("Y-m-d", $activeUntil);
    $timestamp = $activeUntil->format('U');
    $daysLeft = ((int) (new DrupalDateTime())->format('U') - (int) $timestamp) / 60 / 60 / 24;
    $form['subscription'] = $this->buildDefaultContainer($this->t('Subscription'));
    $form['subscription']['active_tier'] = [
      '#type' => 'markup',
      '#theme' => 'active_tier',
      '#tier' => $activeTier,
      '#date' => $activeUntil->format('d.m.Y'),
      '#timestamp' => $timestamp,
      '#days_left' => (string) $daysLeft,
    ];
    $form['subscription']['code'] = $this->buildTextField($this->t('Subscription code'));
    $form['subscription']['redeem'] = [
      '#type' => 'submit',
      '#value' => $this->t('Redeem'),
      '#submit' => [
        '::redeemSubscriptionCode',
      ],
    ];

    $form['profiles'] = $this->buildDefaultContainer($this->t('Profiles'));
    $form['profiles']['active_profile'] = $this->buildSelectField($this->t('Active profile'), $options, $account->getActiveProfile()->getId(), TRUE);

    $form['appearance'] = $this->buildDefaultContainer($this->t('Appearance settings'));
    $form['appearance']['font_size'] = $this->buildSelectField($this->t('Font size'), FontSize::translatableFormOptions(), $account->getFontSize());
    $form['appearance']['color_mode'] = $this->buildSelectField($this->t('Color mode'), ColorMode::translatableFormOptions(), $account->getColorMode());
    $form['appearance']['color_shade'] = $this->buildSelectField($this->t('Color shade'), ColorShade::translatableFormOptions(), $account->getColorShade());

    $form['notifications'] = $this->buildDefaultContainer($this->t('Notifications'));
    $form['notifications']['table'] = [
      '#type' => 'table',
      '#sticky' => TRUE
    ];

    foreach (['-' => $this->t('Type')] + NotificationChannel::translatableFormOptions() as $value => $translation) {
      $form['notifications']['table']['#header'][] = [
        'data' => $translation,
        'class' => ['checkbox'],
      ];
    }

    foreach (NotificationType::translatableFormOptions() as $value => $translation) {
      $form['notifications']['table'][$value] = [
        [
          '#wrapper_attributes' => [
            'colspan' => 1,
            'class' => ['module'],
            'id' => 'channel-' . $value,
          ],
          '#markup' => $translation,
        ],
      ];

      foreach (NotificationChannel::translatableFormOptions() as $value1 => $translation1) {
        $form['notifications']['table'][$value][$value1] = [
          '#title' => $value . ': ' . $value1,
          '#title_display' => 'invisible',
          '#wrapper_attributes' => [
            'class' => ['checkbox'],
          ],
          '#type' => 'checkbox',
          '#default_value' => 0,
          '#parents' => [$value1, $value],
        ];
      }
    }

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    // TODO: Implement submitForm() method.
  }

  public function redeemSubscriptionCode(array &$form, FormStateInterface $form_state) {

  }

}
