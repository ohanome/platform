<?php

namespace Drupal\ohano_account\EventSubscriber;

use Drupal\ohano_account\Event\UserRegisterEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserRegisterSubscriber implements EventSubscriberInterface {

  public static function getSubscribedEvents() {
    return [
      UserRegisterEvent::EVENT_NAME => 'onUserRegister',
    ];
  }

  public function onUserRegister(UserRegisterEvent $event) {
    \Drupal::messenger()->addMessage($event::EVENT_NAME . ' fired!');
  }

}
