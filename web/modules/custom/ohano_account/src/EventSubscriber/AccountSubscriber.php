<?php

namespace Drupal\ohano_account\EventSubscriber;

use Drupal\ohano_account\Entity\AccountActivation;
use Drupal\ohano_account\Event\AccountEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AccountSubscriber implements EventSubscriberInterface {

  public static function getSubscribedEvents() {
    return [
      AccountEvent::CREATE => 'onAccountCreate',
    ];
  }

  public function onAccountCreate(AccountEvent $event) {
    $account = $event->account;

    $user = $account->getUser();
    $username = $user->getAccountName();
    $email = $user->getEmail();

    // Create account activation
    AccountActivation::create()
      ->setUsername($username)
      ->setEmail($email)
      ->setCode(AccountActivation::generateRandomString(64))
      ->setIsValid(FALSE)
      ->save();
  }

}
