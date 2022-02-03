<?php

namespace Drupal\ohano_account\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AccountController extends ControllerBase {

  public function redirectToRegisterPage(): RedirectResponse {
    return new RedirectResponse('/account/register');
  }

}
