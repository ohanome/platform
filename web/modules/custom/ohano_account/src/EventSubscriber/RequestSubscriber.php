<?php

namespace Drupal\ohano_account\EventSubscriber;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Url;
use Drupal\ohano_account\Entity\AccountVerification;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Subscribes to every page request.
 *
 * @package Drupal\ohano_account\EventSubscriber
 */
class RequestSubscriber implements EventSubscriberInterface {

  use StringTranslationTrait;

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents(): array {
    return [
      KernelEvents::REQUEST => 'onRequest',
    ];
  }

  /**
   * Gets called on every request.
   *
   * @param \Symfony\Component\HttpKernel\Event\RequestEvent $event
   *   The event object.
   */
  public function onRequest(RequestEvent $event): void {
    $verificationRoute = Url::fromRoute('ohano_account.verification')
      ->toString();
    $userIsLoggedIn = \Drupal::currentUser()->isAuthenticated();
    if ($event->getRequest()->getPathInfo() != $verificationRoute && $userIsLoggedIn && \Drupal::currentUser()->id() != 1) {
      $account = \Drupal::currentUser();

      $verificationId = \Drupal::entityQuery('account_verification')
        ->condition('user', $account->id())
        ->execute();

      if (empty($verificationId)) {
        \Drupal::messenger()->addError(t('Something went wrong. Please contact the support and tell them the following timestamp: @timestamp', ['timestamp' => (new DrupalDateTime())->format('d-m-Y H:i:s')]));
        \Drupal::logger('ohano_account')->critical("No verification entity found.");
        user_logout();
        return;
      }

      $verification = AccountVerification::load(array_values($verificationId)[0]);

      if (empty($verification)) {
        \Drupal::messenger()->addError(t('Something went wrong. Please contact the support and tell them the following timestamp: @timestamp', ['timestamp' => (new DrupalDateTime())->format('d-m-Y H:i:s')]));
        \Drupal::logger('ohano_account')->critical("Verification could not be loaded.");
        user_logout();
        return;
      }

      if (!$verification->isVerified()) {
        \Drupal::messenger()->addError($this->t("You need to complete the verification first"));
        (new RedirectResponse($verificationRoute))->send();
      }
    }
  }

}
