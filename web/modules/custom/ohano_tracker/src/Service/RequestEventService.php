<?php

namespace Drupal\ohano_tracker\Service;

use Drupal\Core\Entity\EntityStorageException;
use Drupal\ohano_tracker\Entity\Day;
use Drupal\ohano_tracker\Entity\Month;
use Drupal\ohano_tracker\Entity\PathRequest;
use Drupal\ohano_tracker\Entity\Platform;
use Drupal\ohano_tracker\Entity\RequestTime;
use Drupal\ohano_tracker\Entity\UserAgent;
use Drupal\ohano_tracker\Entity\Weekday;
use Drupal\ohano_tracker\Entity\Year;
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

      $userAgent = $request->headers->get('user-agent') ?? 'unknown';
      $userAgentEntity = UserAgent::loadOrCreateByUserAgent($userAgent);
      $userAgentEntity->setCount($userAgentEntity->getCount() + 1);
      $userAgentEntity->save();

      $platform = $request->headers->get('sec-ch-ua-platform') ?? 'unknown';
      $platformEntity = Platform::loadOrCreateByPlatform($platform);
      $platformEntity->setCount($platformEntity->getCount() + 1);
      $platformEntity->save();

      $now = new \DateTime('now', new \DateTimeZone('UTC'));
      $requestTimeEntity = RequestTime::loadOrCreateByTime($now);
      $requestTimeEntity->setCount($requestTimeEntity->getCount() + 1);
      $requestTimeEntity->save();

      $weekdayEntity = Weekday::loadOrCreateByWeekday($now);
      $weekdayEntity->setCount($weekdayEntity->getCount() + 1);
      $weekdayEntity->save();

      $yearEntity = Year::loadOrCreateByYear($now);
      $yearEntity->setCount($yearEntity->getCount() + 1);
      $yearEntity->save();

      $monthEntity = Month::loadOrCreateByMonth($now);
      $monthEntity->setCount($monthEntity->getCount() + 1);
      $monthEntity->save();

      $dayEntity = Day::loadOrCreateByDay($now);
      $dayEntity->setCount($dayEntity->getCount() + 1);
      $dayEntity->save();
    });

    try {
      $fiber->start();
    } catch (\Throwable $e) {
      \Drupal::logger('ohano_tracker')->error($e->getMessage());
    }
  }

}
