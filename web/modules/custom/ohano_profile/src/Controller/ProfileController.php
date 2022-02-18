<?php

namespace Drupal\ohano_profile\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ProfileController extends ControllerBase {

  public function createProfileBase() {
    $destination = NULL;
    if (!empty(\Drupal::request()->query->get('destination'))) {
      $destination = \Drupal::request()->query->get('destination');
    }

    $redirect = new RedirectResponse($destination ?? '/');

    $user = \Drupal::currentUser();

    $account =
    dd($user);
  }

}
