<?php

namespace Drupal\ohano_core\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\ohano_account\Entity\Account;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Provides a controller for the developer mode.
 */
class DeveloperModeController extends ControllerBase {

  /**
   * Switches the developer mode.
   *
   * @return \Symfony\Component\HttpFoundation\RedirectResponse
   *   A redirect response.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function switchDeveloperMode(): RedirectResponse {
    $account = Account::forActive();
    $developerMode = (bool) $account->get('developer_mode')->value;
    $account->set('developer_mode', !$developerMode);
    $account->save();
    drupal_flush_all_caches();

    return new RedirectResponse(\Drupal::request()->headers->get('referer'));
  }

}
