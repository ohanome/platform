<?php

namespace Drupal\ohano_account\Controller\Admin;

use Drupal\Core\Controller\ControllerBase;
use Drupal\ohano_account\Entity\AccountVerification;

/**
 * Controller for all administrative actions about verifications.
 *
 * @package Drupal\ohano_account\Controller\Admin
 */
class VerificationController extends ControllerBase {

  /**
   * Builds and renders the list view.
   *
   * @return array
   *   The render array.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function index() {
    $verificationIds = $this->entityTypeManager()
      ->getStorage('account_verification')
      ->getQuery()
      ->sort('created', 'DESC')
      ->sort('verified')
      ->execute();
    $verificationIds = array_values($verificationIds);
    /** @var \Drupal\ohano_account\Entity\AccountVerification[] $verifications */
    $verifications = AccountVerification::loadMultiple($verificationIds);

    $renderedVerifications = [];
    foreach ($verifications as $verification) {
      $renderedVerifications[] = $verification->render();
    }

    return [
      '#theme' => 'account_verification_list',
      '#verifications' => $renderedVerifications,
    ];
  }

}
