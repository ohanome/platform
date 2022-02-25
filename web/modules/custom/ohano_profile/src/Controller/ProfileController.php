<?php

namespace Drupal\ohano_profile\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\ohano_account\Entity\Account;
use Drupal\ohano_profile\Entity\BaseProfile;
use Drupal\ohano_profile\Entity\UserProfile;
use Drupal\user\Entity\User;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class ProfileController extends ControllerBase {

  public function createProfileBase() {
    $destination = NULL;
    if (!empty(\Drupal::request()->query->get('destination'))) {
      $destination = \Drupal::request()->query->get('destination');
    }

    $redirect = new RedirectResponse($destination ?? '/');

    $user = \Drupal::currentUser();
    $account = Account::getByUser($user);

    if ($profile = UserProfile::loadByUser($user)) {
      \Drupal::logger('ohano_profile')->warning("UserProfile entity already exists.");
    }
    else {
      $profile = UserProfile::create()
        ->setAccount($account)
        ->setStatus(TRUE)
        ->setIsExcludedFromSearch(FALSE);
      $profile->save();
    }

    if ($baseProfile = BaseProfile::loadByProfile($profile)) {
      \Drupal::logger('ohano_profile')->warning("BaseProfile entity already exists.");
    }
    else {
      $baseProfile = BaseProfile::create()
        ->setUsername($user->getAccountName())
        ->setProfile($profile);
      $baseProfile->save();
    }

    return $redirect;
  }

  public function profile($username = NULL) {
    if (empty($username)) {
      $username = \Drupal::currentUser()->getAccountName();
      return new RedirectResponse("/user/$username");
    }

    /** @var User $user */
    $user = user_load_by_name($username);
    if (empty($user)) {
      return new Response('', 404);
    }

    $userProfile = UserProfile::loadByUser($user);
    if (empty($userProfile)) {
      return new Response('', 404);
    }

    dd($userProfile);
  }

  public function redirectToProfile($username = NULL, $uid = NULL) {
    if ($username) {
      return new RedirectResponse("/user/$username");
    }
    if ($uid) {
      return new RedirectResponse("/user/$uid");
    }

    return new RedirectResponse("/user");
  }

}
