<?php

namespace Drupal\ohano_stats\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\ohano_stats\Service\StatisticsService;
use Drupal\ohano_tracker\Entity\Day;
use Drupal\ohano_tracker\Entity\Month;
use Drupal\ohano_tracker\Entity\PathRequest;
use Drupal\ohano_tracker\Entity\Platform;
use Drupal\ohano_tracker\Entity\RequestTime;
use Drupal\ohano_tracker\Entity\UserAgent;
use Drupal\ohano_tracker\Entity\Weekday;
use Drupal\ohano_tracker\Entity\Year;
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

  public function getStatistics() {
    $currentUser = \Drupal::currentUser();
    $statistics = [];

    if ($currentUser->hasPermission('ohano stats access user stats')) {
      $statistics[] = [
        '#theme' => 'statistics_row',
        '#row_name' => $this->t('User Statistics'),
        '#statistic_container' => $this->getUserStatistics(),
      ];
    }

    if ($currentUser->hasPermission('ohano stats access profile stats')) {
      $statistics[] = [
        '#theme' => 'statistics_row',
        '#row_name' => $this->t('Profile Statistics'),
        '#statistic_container' => $this->getProfileStatistics(),
      ];
    }

    if ($currentUser->hasPermission('ohano stats access subprofile stats')) {
      $statistics[] = [
        '#theme' => 'statistics_row',
        '#row_name' => $this->t('Sub-Profile Statistics'),
        '#statistic_container' => $this->getSubProfileStatistics(),
      ];
    }

    if ($currentUser->hasPermission('ohano stats access route stats')) {
      $statistics[] = [
        '#theme' => 'statistics_row',
        '#row_name' => $this->t('Visited Pages Statistics'),
        '#statistic_container' => $this->getVisitedPagesStatistics(),
      ];
    }

    if ($currentUser->hasPermission('ohano stats access useragent stats')) {
      $statistics[] = [
        '#theme' => 'statistics_row',
        '#row_name' => $this->t('User Agent Statistics'),
        '#statistic_container' => $this->getUserAgentStatistics(),
      ];
    }

    if ($currentUser->hasPermission('ohano stats access platform stats')) {
      $statistics[] = [
        '#theme' => 'statistics_row',
        '#row_name' => $this->t('Client Platform Statistics'),
        '#statistic_container' => $this->getPlatformStatistics(),
      ];
    }

    if ($currentUser->hasPermission('ohano stats access request time stats')) {
      $statistics[] = [
        '#theme' => 'statistics_row',
        '#row_name' => $this->t('Request Time Statistics'),
        '#statistic_container' => $this->getRequestTimeStatistics(),
      ];
    }

    if ($currentUser->hasPermission('ohano stats access request day stats')) {
      $statistics[] = [
        '#theme' => 'statistics_row',
        '#row_name' => $this->t('Request Time Statistics'),
        '#statistic_container' => $this->getRequestDayStatistics(),
      ];
    }

    if ($currentUser->hasPermission('ohano stats access request weekday stats')) {
      $statistics[] = [
        '#theme' => 'statistics_row',
        '#row_name' => $this->t('Request Time Statistics'),
        '#statistic_container' => $this->getRequestWeekdayStatistics(),
      ];
    }

    if ($currentUser->hasPermission('ohano stats access request month stats')) {
      $statistics[] = [
        '#theme' => 'statistics_row',
        '#row_name' => $this->t('Request Time Statistics'),
        '#statistic_container' => $this->getRequestMonthStatistics(),
      ];
    }

    if ($currentUser->hasPermission('ohano stats access request year stats')) {
      $statistics[] = [
        '#theme' => 'statistics_row',
        '#row_name' => $this->t('Request Time Statistics'),
        '#statistic_container' => $this->getRequestYearStatistics(),
      ];
    }

    return [
      '#theme' => 'statistics_page',
      '#statistic_rows' => $statistics,
      '#cache' => [
        'max-age' => 0,
      ],
    ];
  }

  public function getUserStatistics() {
    $statistics = [];

    $statistics[] = $this->getRegisteredUserCount();
    $statistics[] = $this->getDailyActiveUsers();
    $statistics[] = $this->getWeeklyActiveUsers();
    $statistics[] = $this->getMonthlyActiveUsers();
    $statistics[] = $this->getYearlyActiveUsers();

    return [
      '#theme' => 'statistic_container',
      '#statistics' => $statistics,
    ];
  }

  public function getProfileStatistics() {
    $statistics = [];

    $statistics[] = $this->getAllUserProfiles();
    $statistics[] = $this->getPersonalUserProfiles();
    $statistics[] = $this->getCompanyUserProfiles();
    $statistics[] = $this->getArtistUserProfiles();
    $statistics[] = $this->getMusicianUserProfiles();
    $statistics[] = $this->getInfluencerUserProfiles();
    $statistics[] = $this->getStreamerUserProfiles();

    return [
      '#theme' => 'statistic_container',
      '#statistics' => $statistics,
    ];
  }

  public function getSubProfileStatistics() {
    $statistics = [];

    $statistics[] = $this->getSubProfiles();
    $statistics[] = $this->getBaseProfiles();
    $statistics[] = $this->getCodingProfiles();
    $statistics[] = $this->getGamingProfiles();
    $statistics[] = $this->getJobProfiles();
    $statistics[] = $this->getRelationshipProfiles();

    return [
      '#theme' => 'statistic_container',
      '#statistics' => $statistics,
    ];
  }

  public function getVisitedPagesStatistics() {
    $statistics = [];
    $trackerQuery = \Drupal::entityQuery(PathRequest::entityTypeId())
      ->sort('count', 'DESC');
    $trackerResult = $trackerQuery->execute();
    foreach ($trackerResult as $pathRequestId) {
      $pathRequest = PathRequest::load($pathRequestId);
      $statistics[] = [
        'name' => $pathRequest->getPath(),
        'value' => $pathRequest->getCount(),
      ];
    }

    return [
      '#theme' => 'statistic_container',
      '#statistics' => $statistics,
    ];
  }

  public function getUserAgentStatistics() {
    $statistics = [];
    $trackerQuery = \Drupal::entityQuery(UserAgent::entityTypeId())
      ->sort('count', 'DESC');
    $trackerResult = $trackerQuery->execute();
    foreach ($trackerResult as $pathRequestId) {
      $pathRequest = UserAgent::load($pathRequestId);
      $statistics[] = [
        'name' => $pathRequest->getUserAgent(),
        'value' => $pathRequest->getCount(),
      ];
    }

    return [
      '#theme' => 'statistic_container',
      '#statistics' => $statistics,
    ];
  }

  public function getPlatformStatistics() {
    $statistics = [];
    $trackerQuery = \Drupal::entityQuery(Platform::entityTypeId())
      ->sort('count', 'DESC');
    $trackerResult = $trackerQuery->execute();
    foreach ($trackerResult as $pathRequestId) {
      $pathRequest = Platform::load($pathRequestId);
      $statistics[] = [
        'name' => $pathRequest->getPlatform(),
        'value' => $pathRequest->getCount(),
      ];
    }

    return [
      '#theme' => 'statistic_container',
      '#statistics' => $statistics,
    ];
  }

  public function getRequestTimeStatistics() {
    $statistics = [];
    $trackerQuery = \Drupal::entityQuery(RequestTime::entityTypeId())
      ->sort('time');
    $trackerResult = $trackerQuery->execute();
    foreach ($trackerResult as $pathRequestId) {
      $pathRequest = RequestTime::load($pathRequestId);
      $statistics[] = [
        'name' => $pathRequest->get('time')->value,
        'value' => $pathRequest->getCount(),
      ];
    }

    return [
      '#theme' => 'statistic_container',
      '#statistics' => $statistics,
    ];
  }

  public function getRequestDayStatistics() {
    $statistics = [];
    $trackerQuery = \Drupal::entityQuery(Day::entityTypeId())
      ->sort('day');
    $trackerResult = $trackerQuery->execute();
    foreach ($trackerResult as $pathRequestId) {
      $pathRequest = Day::load($pathRequestId);
      $statistics[] = [
        'name' => $pathRequest->get('day')->value,
        'value' => $pathRequest->getCount(),
      ];
    }

    return [
      '#theme' => 'statistic_container',
      '#statistics' => $statistics,
    ];
  }

  public function getRequestWeekdayStatistics() {
    $statistics = [];
    $trackerQuery = \Drupal::entityQuery(Weekday::entityTypeId())
      ->sort('count');
    $trackerResult = $trackerQuery->execute();
    foreach ($trackerResult as $pathRequestId) {
      $pathRequest = Weekday::load($pathRequestId);
      $statistics[] = [
        'name' => $pathRequest->get('weekday')->value,
        'value' => $pathRequest->getCount(),
      ];
    }

    return [
      '#theme' => 'statistic_container',
      '#statistics' => $statistics,
    ];
  }

  public function getRequestMonthStatistics() {
    $statistics = [];
    $trackerQuery = \Drupal::entityQuery(Month::entityTypeId())
      ->sort('count');
    $trackerResult = $trackerQuery->execute();
    foreach ($trackerResult as $pathRequestId) {
      $pathRequest = Month::load($pathRequestId);
      $statistics[] = [
        'name' => $pathRequest->get('month')->value,
        'value' => $pathRequest->getCount(),
      ];
    }

    return [
      '#theme' => 'statistic_container',
      '#statistics' => $statistics,
    ];
  }

  public function getRequestYearStatistics() {
    $statistics = [];
    $trackerQuery = \Drupal::entityQuery(Year::entityTypeId())
      ->sort('count');
    $trackerResult = $trackerQuery->execute();
    foreach ($trackerResult as $pathRequestId) {
      $pathRequest = Year::load($pathRequestId);
      $statistics[] = [
        'name' => $pathRequest->get('year')->value,
        'value' => $pathRequest->getCount(),
      ];
    }

    return [
      '#theme' => 'statistic_container',
      '#statistics' => $statistics,
    ];
  }

  public function getRegisteredUserCount() {
    return [
      'name' => $this->t('Registered Users'),
      'value' => (string) $this->statisticService->countRegisteredUsers(),
    ];
  }

  public function getDailyActiveUsers() {
    return [
      'name' => $this->t('Daily Active Users'),
      'value' => (string) $this->statisticService->countActiveUsersForPastDay(),
      'after' => $this->t('were active within the past 24 hours'),
    ];
  }

  public function getWeeklyActiveUsers() {
    return [
      'name' => $this->t('Weekly Active Users'),
      'value' => (string) $this->statisticService->countActiveUsersForPastWeek(),
      'after' => $this->t('were active within the past 7 days'),
    ];
  }

  public function getMonthlyActiveUsers() {
    return [
      'name' => $this->t('Monthly Active Users'),
      'value' => (string) $this->statisticService->countActiveUsersForPastMonth(),
      'after' => $this->t('were active within the past month'),
    ];
  }

  public function getYearlyActiveUsers() {
    return [
      'name' => $this->t('Yearly Active Users'),
      'value' => (string) $this->statisticService->countActiveUsersForPastYear(),
      'after' => $this->t('were active within the past year'),
    ];
  }

  public function getAllUserProfiles() {
    return [
      'name' => $this->t('All Profiles'),
      'value' => (string) $this->statisticService->countAllUserProfiles(),
    ];
  }

  public function getPersonalUserProfiles() {
    return [
      'name' => $this->t('Personal Profiles'),
      'value' => (string) $this->statisticService->countPersonalUserProfiles(),
    ];
  }

  public function getCompanyUserProfiles() {
    return [
      'name' => $this->t('Company Profiles'),
      'value' => (string) $this->statisticService->countCompanyUserProfiles(),
    ];
  }

  public function getArtistUserProfiles() {
    return [
      'name' => $this->t('Artist Profiles'),
      'value' => (string) $this->statisticService->countArtistUserProfiles(),
    ];
  }

  public function getMusicianUserProfiles() {
    return [
      'name' => $this->t('Musician Profiles'),
      'value' => (string) $this->statisticService->countMusicianUserProfiles(),
    ];
  }

  public function getInfluencerUserProfiles() {
    return [
      'name' => $this->t('Influencer Profiles'),
      'value' => (string) $this->statisticService->countInfluencerUserProfiles(),
    ];
  }

  public function getStreamerUserProfiles() {
    return [
      'name' => $this->t('Streamer Profiles'),
      'value' => (string) $this->statisticService->countStreamerUserProfiles(),
    ];
  }

  public function getSubProfiles() {
    return [
      'name' => $this->t('All Sub-Profiles'),
      'value' => (string) ($this->statisticService->countBaseProfiles() + $this->statisticService->countCodingProfiles() + $this->statisticService->countGamingProfiles() + $this->statisticService->countJobProfiles() + $this->statisticService->countRelationshipProfiles() + $this->statisticService->countSocialMediaProfiles()),
    ];
  }

  public function getBaseProfiles() {
    return [
      'name' => $this->t('Sub-Profiles of type "Base"'),
      'value' => (string) $this->statisticService->countBaseProfiles(),
    ];
  }

  public function getCodingProfiles() {
    return [
      'name' => $this->t('Sub-Profiles of type "Coding"'),
      'value' => (string) $this->statisticService->countCodingProfiles(),
    ];
  }

  public function getGamingProfiles() {
    return [
      'name' => $this->t('Sub-Profiles of type "Gaming"'),
      'value' => (string) $this->statisticService->countGamingProfiles(),
    ];
  }

  public function getJobProfiles() {
    return [
      'name' => $this->t('Sub-Profiles of type "Job"'),
      'value' => (string) $this->statisticService->countJobProfiles(),
    ];
  }

  public function getRelationshipProfiles() {
    return [
      'name' => $this->t('Sub-Profiles of type "Relationship"'),
      'value' => (string) $this->statisticService->countRelationshipProfiles(),
    ];
  }

}
