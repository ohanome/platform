<?php

namespace Drupal\ohano_profile\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\ohano_account\Blocklist;
use Drupal\ohano_account\Entity\Account;
use Drupal\ohano_account\Validator\UsernameValidator;
use Drupal\ohano_core\Form\FormTrait;
use Drupal\ohano_profile\Entity\BaseProfile;
use Drupal\ohano_profile\Entity\UserProfile;
use Drupal\ohano_profile\Option\ProfileType;
use Drupal\user\Entity\User;

class ProfileAddForm extends FormBase {
  use FormTrait;

  public function getFormId() {
    return 'ohano_profile_profile_add';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = [];

    $form['profile_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Name'),
      '#description' => $this->t('Only letters, numbers and the special characters -_. are allowed. Everything else will be replaced automatically. At the start only letters are allowed and at the end only letters and numbers are allowed.'),
      '#required' => TRUE,
      '#pattern' => '^[\w]{4,24}$',
    ];

    $typeOptions = ProfileType::translatableFormOptions();
    unset($typeOptions[ProfileType::Personal->value]);
    $form['type'] = [
      '#type' => 'select',
      '#title' => $this->t('Profile type'),
      '#options' => [NULL => '-'] + $typeOptions,
      '#required' => TRUE,
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add profile'),
    ];

    $form['#attached']['library'][] = 'ohano_profile/profile-add-form';
    $form['#cache'] = [
      'max-age' => 0,
    ];

    return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
    /** @var \Drupal\ohano_account\Validator\UsernameValidator $usernameValidator */
    $usernameValidator = \Drupal::service('ohano_account.validator.username');
    $values = $form_state->getValues();
    if ($usernameValidator->validateUsername($values['profile_name']) != UsernameValidator::VALID) {
      $form_state->setErrorByName('profile_name', $this->t('This profile name is already taken.'));
    }

    if (ProfileType::tryFrom($values['type']) == NULL) {
      $form_state->setErrorByName('type', $this->t('The selected profile type is invalid.'));
    }
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    $profileName = $values['profile_name'];
    $profileName = str_replace(' ', '-', $profileName);

    $type = $values['type'];

    $user = User::load(\Drupal::currentUser()->id());
    $account = Account::getByUser($user);
    $userProfile = UserProfile::create()
      ->setAccount($account)
      ->setStatus(TRUE)
      ->setProfileName($profileName)
      ->setType($type)
      ->setIsExcludedFromSearch(TRUE);
    $userProfile->save();

    $baseProfile = BaseProfile::create()
      ->setProfile($userProfile);
    $baseProfile->save();

    $this->messenger()->addMessage($this->t('Your new profile has been created. You can now edit it.'));
  }

}
