<?php

namespace Drupal\ohano_core\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\ohano_account\Entity\Account;
use Symfony\Component\HttpFoundation\RedirectResponse;

class DeveloperModeController extends ControllerBase {

  public function switchDeveloperMode() {
    $account = Account::forActive();
    $developerMode = (bool) $account->get('developer_mode')->value;
    $account->set('developer_mode', !$developerMode);
    $account->save();

    return new RedirectResponse(\Drupal::request()->headers->get('referer'));
  }

}
