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
    if (!(\Drupal::currentUser()->isAnonymous() || (\Drupal::currentUser()->isAuthenticated() && count(\Drupal::currentUser()->getRoles()) === 1))) {
      return;
    }
    $path = $event->getRequest()->getPathInfo();
    $pathRequestEntity = PathRequest::create([
      'path' => $path,
    ]);
    try {
      $pathRequestEntity->save();
    } catch (EntityStorageException $e) {
      \Drupal::logger('ohano_tracker')->error($e->getMessage());
    }
  }

}
