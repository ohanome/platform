<?php

namespace Drupal\ohano_tracker\EventSubscriber;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Entity\EntityStorageException;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Url;
use Drupal\ohano_account\Entity\Account;
use Drupal\ohano_account\Entity\AccountVerification;
use Drupal\ohano_core\OhanoCore;
use Drupal\ohano_tracker\Entity\PathRequest;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Subscribes to every page request.
 *
 * @package Drupal\ohano_tracker\EventSubscriber
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
    /** @var \Drupal\ohano_tracker\Service\RequestEventService $service */
    $service = \Drupal::service('ohano_tracker.request_event');
    $service->handleEvent($event);
  }

}
