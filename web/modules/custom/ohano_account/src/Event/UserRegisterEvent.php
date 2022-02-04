<?php

namespace Drupal\ohano_account\Event;

use Drupal\Component\EventDispatcher\Event;
use Drupal\Core\Session\AccountInterface;

class UserRegisterEvent extends Event {

  const EVENT_NAME = 'ohano_account_user_register';

  public function __construct(public AccountInterface $user) {

  }

}
