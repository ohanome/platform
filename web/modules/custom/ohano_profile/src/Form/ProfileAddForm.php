<?php

namespace Drupal\ohano_profile\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\ohano_account\Blocklist;
use Drupal\ohano_account\Entity\Account;
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
      '#required' => TRUE,
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

    return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    $foundProfile = UserProfile::loadByName($values['profile_name']);
    if (in_array($values['profile_name'], Blocklist::USERNAME) || !empty($foundProfile)) {
      $form_state->setErrorByName('profile_name', $this->t('This profile name is already taken.'));
    }

    if (ProfileType::tryFrom($values['type']) == NULL) {
      $form_state->setErrorByName('type', $this->t('The selected profile type is invalid.'));
    }
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    $profileName = $values['profile_name'];
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
