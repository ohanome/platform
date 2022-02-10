<?php

namespace Drupal\ohano_account\Controller\Admin;

use Drupal\Core\Controller\ControllerBase;
use Drupal\ohano_account\Entity\AccountVerification;

class VerificationController extends ControllerBase {

  public function index() {
    $verificationIds = $this->entityTypeManager()
      ->getStorage('account_verification')
      ->getQuery()
//      ->condition('verified', 'null')
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

    #dd($renderedVerifications);

    return [
      '#theme' => 'account_verification_list',
      '#verifications' => $renderedVerifications,
    ];
  }

}
