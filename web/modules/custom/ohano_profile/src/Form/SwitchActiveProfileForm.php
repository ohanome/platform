<?php

namespace Drupal\ohano_profile\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\ohano_account\Entity\Account;
use Drupal\ohano_profile\Entity\UserProfile;

class SwitchActiveProfileForm extends FormBase {

  public function getFormId() {
    return 'ohano_profile_switch_active_profile';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = [];

    $profiles = UserProfile::loadMultipleByUser(\Drupal::currentUser());
    $options = [];
    foreach ($profiles as $profile) {
      $options[$profile->getId()] = $profile->getProfileName();
    }

    $account = Account::forActive();

    $form['active_profile'] = [
      '#type' => 'select',
      '#title' => $this->t('Active profile'),
      '#options' => $options,
      '#default_value' => $account->getActiveProfile()->getId(),
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
    ];

    return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
    $account = Account::forActive();
    $activeProfileId = $form_state->getValue('active_profile');
    $activeProfile = UserProfile::load($activeProfileId);
    if ($activeProfile->getAccount()->getId() != $account->getId()) {
      $form_state->setErrorByName('active_profile', $this->t("You don't own the selected profile."));
    }
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $account = Account::forActive();
    $activeProfileId = $form_state->getValue('active_profile');
    $activeProfile = UserProfile::load($activeProfileId);
    $account->setActiveProfile($activeProfile);
    $account->save();
  }

}
