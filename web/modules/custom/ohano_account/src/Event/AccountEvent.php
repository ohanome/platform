<?php

namespace Drupal\ohano_account\Event;

use Drupal\Component\EventDispatcher\Event;
use Drupal\ohano_account\Entity\Account;

class AccountEvent extends Event {

  const CREATE = 'ohano_account_account_create';
  const UPDATE = 'ohano_account_account_update';
  const DELETE = 'ohano_account_account_delete';

  public function __construct(public Account $account) {

  }

}
