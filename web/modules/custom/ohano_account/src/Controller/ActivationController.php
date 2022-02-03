<?php

namespace Drupal\ohano_account\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\ohano_account\Entity\AccountActivation;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ActivationController extends ControllerBase {

  public function activateAccount(string $code) {
    if (\Drupal::currentUser()->isAnonymous()) {
      $path = '/user/login?destination=/account/activate/' . $code;
      \Drupal::messenger()->addError($this->t('You need to be logged in to activate your account.'));
      return new RedirectResponse($path);
    }

    $activation = \Drupal::entityQuery('account_activation')
      ->condition('username', \Drupal::currentUser()->getAccountName())
      ->execute();

    $activation = AccountActivation::load(array_values($activation)[0]);
    if ($code == $activation->getCode()) {
      $activation->setActivatedOn(new DrupalDateTime());
      $activation->setIsValid(TRUE);
      $activation->save();

      \Drupal::messenger()->addError($this->t('Your account has been activated successfully. You can now continue.'));

      $path = '/';
    } else {
      \Drupal::messenger()->addError($this->t('The activation link was invalid.'));
      $path = '/account/activate';
    }

    return new RedirectResponse($path);
  }

}
