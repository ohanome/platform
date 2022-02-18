<?php

namespace Drupal\ohano_profile\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\ohano_account\Entity\Account;
use Drupal\ohano_core\Error\Error;
use Drupal\ohano_profile\Entity\BaseProfile;
use Drupal\ohano_profile\Entity\UserProfile;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ProfileForm extends FormBase {

  public function getFormId() {
    return 'ohano_profile_profile';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = [];

    $currentUser = \Drupal::currentUser();
    $userProfile = UserProfile::loadByUser($currentUser);
    if (empty($userProfile)) {
      (new RedirectResponse('/profile/create-base?destination=/profile/edit'))->send();
    }

    $baseProfile = BaseProfile::loadByUser($currentUser);
    if (empty($baseProfile)) {
      $this->messenger()->addError($this->t("Oops, that looks wrong. We're sorry about that. Please contact the support with the following error code: @error", ['@error' => Error::BaseProfileNotFound->value]));
      return [];
    }

    $form['base_profile'] = [
      '#type' => 'details',
      '#tree' => TRUE,
      '#title' => $this->t('Base profile')
    ];

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    // TODO: Implement submitForm() method.
  }

}
