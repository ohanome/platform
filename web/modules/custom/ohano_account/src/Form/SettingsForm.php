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
    $activeUntil->setTime(0, 0);

    $now = new DrupalDateTime();
    $now->setTime(0, 0);

    $timestamp = (int) $activeUntil->format('U');
    $timestampNow = (int) $now->format('U');
    $daysLeft = ($timestamp - $timestampNow) / 60 / 60 / 24;
    $daysLeft = (int) $daysLeft;

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
    foreach (NotificationType::cases() as $type) {
      $form['notifications'][$type->name] = $this->buildSelectField($this->t($type->value), [NULL => $this->t('None')] + NotificationChannel::translatableFormOptions());
    }

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    // TODO: Implement submitForm() method.
  }

  public function redeemSubscriptionCode(array &$form, FormStateInterface $form_state) {

  }

}
