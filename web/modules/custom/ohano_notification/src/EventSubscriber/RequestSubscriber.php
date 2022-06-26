<?php

namespace Drupal\ohano_notification\EventSubscriber;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Url;
use Drupal\ohano_account\Entity\Account;
use Drupal\ohano_account\Entity\AccountVerification;
use Drupal\ohano_notification\Entity\NotificationSettings;
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
  public static function getSubscribedEvents() {
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
    $userIsLoggedIn = \Drupal::currentUser()->isAuthenticated();
    if ($userIsLoggedIn) {
      $account = Account::forActive();
      $notificationSettings = NotificationSettings::forAccount($account);
      if (empty($notificationSettings)) {
        /** @var \Drupal\ohano_notification\Service\NotificationService $notificationService */
        $notificationService = \Drupal::service('ohano_notification.notification');
        $notificationService->createNotificationSettingsForAccount($account);
      }
    }
  }

}
