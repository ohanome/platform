<?php

namespace Drupal\ohano_stats\Controller\Api;

use Drupal\Core\Controller\ControllerBase;
use Drupal\ohano_stats\Service\StatisticsService;
use Laminas\Diactoros\Response\JsonResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;

class StatisticsController extends ControllerBase {

  protected StatisticsService $statisticService;

  public function __construct(StatisticsService $statisticsService) {
    $this->statisticService = $statisticsService;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('ohano_stats.statistic')
    );
  }

  public function countRegisteredUsers(): JsonResponse {
    return new JsonResponse($this->statisticService->countRegisteredUsers());
  }

  public function countActiveUsersForPastDay(): JsonResponse {
    return new JsonResponse($this->statisticService->countActiveUsersForPastYear());
  }

  public function countActiveUsersForPastWeek(): JsonResponse {
    return new JsonResponse($this->statisticService->countActiveUsersForPastYear());
  }

  public function countActiveUsersForPastMonth(): JsonResponse {
    return new JsonResponse($this->statisticService->countActiveUsersForPastYear());
  }

  public function countActiveUsersForPastYear(): JsonResponse {
    return new JsonResponse($this->statisticService->countActiveUsersForPastYear());
  }

}
