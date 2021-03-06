<?php

namespace Drupal\ohano_account\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Entity\EntityStorageException;
use Drupal\ohano_account\Entity\AccountActivation;
use Drupal\user\Entity\User;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Controller class for the activation process.
 *
 * @package Drupal\ohano_account\Controller
 */
class ActivationController extends ControllerBase {

  /**
   * Controls the activation based on the given code.
   *
   * @param string $code
   *   The code to validate.
   *
   * @return \Symfony\Component\HttpFoundation\RedirectResponse
   *   A redirect to a specific page.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function activateAccount(string $code): RedirectResponse {
    try {
      $activation = AccountActivation::findByCode($code);
    }
    catch (\Exception $e) {
      \Drupal::messenger()->addError($this->t('Something went wrong while activating your account.'));
      \Drupal::logger('ohano_account')->critical($e->getMessage());
      return new RedirectResponse('/');
    }

    if ($code == $activation->getCode() && $activation->getActivatedOn() < 1000) {
      $username = $activation->getUsername();
      $email = $activation->getEmail();
      $userQuery = \Drupal::entityQuery('user')
        ->condition('name', $username)
        ->condition('mail', $email)
        ->execute();
      $user = User::load(array_values($userQuery)[0]);

      if (empty($user)) {
        \Drupal::messenger()->addError($this->t('Something went wrong while activating your account.'));
        \Drupal::logger('ohano_account')->critical("User entity could not be loaded. Username: '$username', email: '$email', code: '$code'");
        return new RedirectResponse('/');
      }

      $user->activate();
      try {
        $user->save();
      }
      catch (EntityStorageException $e) {
        \Drupal::messenger()->addError($this->t('Something went wrong while activating your account.'));
        \Drupal::logger('ohano_account')->critical($e->getMessage());
        return new RedirectResponse('/');
      }

      $activation->setActivatedOn(new DrupalDateTime());
      $activation->setIsValid(TRUE);
      $activation->save();

      \Drupal::messenger()->addMessage($this->t("Your account has been activated successfully. You can now continue."));

      $path = '/';
    }
    else {
      \Drupal::messenger()->addError($this->t('The activation link was invalid.'));
      $path = '/account/activate';
    }

    return new RedirectResponse($path);
  }

}
