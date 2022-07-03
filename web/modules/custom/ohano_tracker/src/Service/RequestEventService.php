<?php

namespace Drupal\ohano_tracker\Service;

use Drupal\Core\Entity\EntityStorageException;
use Drupal\ohano_tracker\Entity\PathRequest;
use Drupal\ohano_tracker\Entity\Platform;
use Drupal\ohano_tracker\Entity\UserAgent;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class RequestEventService {

  public function userShouldBeTracked() {
    if (\Drupal::currentUser()->isAnonymous()) {
      return TRUE;
    }

    if (\Drupal::currentUser()->isAuthenticated() && count(\Drupal::currentUser()->getRoles()) === 1) {
      return TRUE;
    }

    return FALSE;
  }

  public function handleEvent(RequestEvent $event) {
    if (!$this->userShouldBeTracked()) {
      return;
    }

    $fiber = new \Fiber(function () use ($event) {
      $request = $event->getRequest();

      $path = $request->getPathInfo();
      $pathRequestEntity = PathRequest::loadOrCreateByPath($path);
      $pathRequestEntity->setCount($pathRequestEntity->getCount() + 1);
      $pathRequestEntity->save();

      $userAgent = $request->headers->get('user-agent');
      $userAgentEntity = UserAgent::loadOrCreateByUserAgent($userAgent);
      $userAgentEntity->setCount($userAgentEntity->getCount() + 1);
      $userAgentEntity->save();

      $platform = $request->headers->get('sec-ch-ua-platform');
      $platformEntity = Platform::loadOrCreateByPlatform($platform);
      $platformEntity->setCount($platformEntity->getCount() + 1);
      $platformEntity->save();
    });

    try {
      $fiber->start();
    } catch (\Throwable $e) {
      \Drupal::logger('ohano_tracker')->error($e->getMessage());
    }
  }

}
