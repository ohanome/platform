<?php

namespace Drupal\ohano_account\Event;

use Drupal\Component\EventDispatcher\Event;
use Drupal\ohano_account\Entity\AccountActivation;

class AccountActivationEvent extends Event {

  const CREATE = 'ohano_account_account_activation_create';
  const UPDATE = 'ohano_account_account_activation_update';
  const DELETE = 'ohano_account_account_activation_delete';
  const ACTIVATE = 'ohano_account_account_activation_activate';

  public function __construct(public AccountActivation $accountActivation) {

  }

}
