<?php

namespace Drupal\ohano_mail\EventSubscriber;

use Drupal\ohano_account\Entity\AccountActivation;
use Drupal\ohano_account\Event\AccountActivationEvent;
use Drupal\ohano_account\Event\AccountEvent;
use Drupal\ohano_mail\OhanoMail;
use Drupal\ohano_mail\OhanoMailer;
use PHPMailer\PHPMailer\Exception;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AccountActivationSubscriber implements EventSubscriberInterface {

  public static function getSubscribedEvents() {
    return [
      AccountActivationEvent::CREATE => 'onAccountActivationCreate',
    ];
  }

  public function onAccountActivationCreate(AccountActivationEvent $event) {
    $accountActivation = $event->accountActivation;
    $username = $accountActivation->getUsername();
    $code = $accountActivation->getCode();
    $host = \Drupal::request()->getHttpHost();
    $link = "$host/account/activate/$code";

    $mailer = new OhanoMailer(OhanoMail::AccountActivation);
    $mailer->renderBody([
      'username' => $username,
      'link' => $link
    ]);

    try {
      $mailer->addAddress($accountActivation->getEmail(), $username);
    } catch (Exception $e) {
      \Drupal::messenger()->addError($e->getMessage());
    }

    try {
      $mailer->send();
    } catch (Exception $e) {
      \Drupal::messenger()->addError($e->getMessage());
    }

    \Drupal::messenger()->addMessage('Last error: ' . $mailer->ErrorInfo);
  }

}
