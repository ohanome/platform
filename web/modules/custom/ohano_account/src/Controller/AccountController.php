<?php

namespace Drupal\ohano_account\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Controller class coordinating every request according to the account.
 *
 * @package Drupal\ohano_account\Controller
 */
class AccountController extends ControllerBase {

  /**
   * Redirects to the register form.
   *
   * @return \Symfony\Component\HttpFoundation\RedirectResponse
   *   The redirect response.
   */
  public function redirectToRegisterPage(): RedirectResponse {
    return new RedirectResponse('/account/register');
  }

}
