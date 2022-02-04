<?php

namespace Drupal\ohano_account\EventSubscriber;

use Drupal\ohano_account\Entity\Account;
use Drupal\ohano_account\Event\UserRegisterEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserRegisterSubscriber implements EventSubscriberInterface {

  public static function getSubscribedEvents() {
    return [
      UserRegisterEvent::EVENT_NAME => 'onUserRegister',
    ];
  }

  public function onUserRegister(UserRegisterEvent $event) {
    $user = $event->user;

    // Create account.
    Account::create()
      ->setUser($user)
      ->setBits(0)
      ->save();
  }

}
