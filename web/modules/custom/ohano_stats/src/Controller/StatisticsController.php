<?php

namespace Drupal\ohano_stats\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
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

/**
 * Provides a controller for the statistics page.
 */
class StatisticsController extends ControllerBase {

  /**
   * The limit of the number of items to display.
   *
   * @var int
   */
  const LIMIT = 10;

  /**
   * The statistics service.
   *
   * @var \Drupal\ohano_stats\Service\StatisticsService
   */
  protected StatisticsService $statisticService;

  /**
   * StatisticsController constructor.
   *
   * @param \Drupal\ohano_stats\Service\StatisticsService $statisticsService
   *   The statistics service.
   */
  public function __construct(StatisticsService $statisticsService) {
    $this->statisticService = $statisticsService;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): StatisticsController|static {
    return new static(
      $container->get('ohano_stats.statistic')
    );
  }

  /**
   * Displays the statistics for visited pages.
   *
   * @return array
   *   A render array for the statistics page.
   */
  public function getPagesStatistics(): array {
    $currentUser = \Drupal::currentUser();
    $statistics = [];

    if ($currentUser->hasPermission('ohano stats access route stats')) {
      $statistics[] = [
        '#theme' => 'statistics_row',
        '#row_name' => $this->t('Visited Pages Statistics'),
        '#statistic_container' => $this->getVisitedPagesStatistics(),
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

  /**
   * Displays the statistics page.
   *
   * @return array
   *   A render array for the statistics page.
   */
  public function getStatistics($stat_type = 'all'): array {
    $currentUser = \Drupal::currentUser();
    $statistics = [];

    if (($stat_type == 'all' || $stat_type == 'user') && $currentUser->hasPermission('ohano stats access user stats')) {
      $statistics[] = [
        '#theme' => 'statistics_row',
        '#row_name' => $this->t('User Statistics'),
        '#statistic_container' => $this->getUserStatistics(),
      ];
    }

    if (($stat_type == 'all' || $stat_type == 'profile') && $currentUser->hasPermission('ohano stats access profile stats')) {
      $statistics[] = [
        '#theme' => 'statistics_row',
        '#row_name' => $this->t('Profile Statistics'),
        '#statistic_container' => $this->getProfileStatistics(),
      ];
    }

    if (($stat_type == 'all' || $stat_type == 'subprofile') && $currentUser->hasPermission('ohano stats access subprofile stats')) {
      $statistics[] = [
        '#theme' => 'statistics_row',
        '#row_name' => $this->t('Sub-Profile Statistics'),
        '#statistic_container' => $this->getSubProfileStatistics(),
      ];
    }

    if (($stat_type == 'all' || $stat_type == 'visited-pages') && $currentUser->hasPermission('ohano stats access route stats')) {
      $statistics[] = [
        '#theme' => 'statistics_row',
        '#row_name' => $this->t('Visited Pages Statistics'),
        '#statistic_container' => $this->getVisitedPagesStatistics($stat_type != 'visited-pages'),
      ];
    }

    if (($stat_type == 'all' || $stat_type == 'user-agent') && $currentUser->hasPermission('ohano stats access useragent stats')) {
      $statistics[] = [
        '#theme' => 'statistics_row',
        '#row_name' => $this->t('User Agent Statistics'),
        '#statistic_container' => $this->getUserAgentStatistics($stat_type != 'user-agent'),
      ];
    }

    if (($stat_type == 'all' || $stat_type == 'client-platform') && $currentUser->hasPermission('ohano stats access platform stats')) {
      $statistics[] = [
        '#theme' => 'statistics_row',
        '#row_name' => $this->t('Client Platform Statistics'),
        '#statistic_container' => $this->getPlatformStatistics($stat_type != 'client-platform'),
      ];
    }

    if (($stat_type == 'all' || $stat_type == 'request-time') && $currentUser->hasPermission('ohano stats access request time stats')) {
      $statistics[] = [
        '#theme' => 'statistics_row',
        '#row_name' => $this->t('Request Time Statistics'),
        '#statistic_container' => $this->getRequestTimeStatistics($stat_type != 'request-time'),
      ];
    }

    if (($stat_type == 'all' || $stat_type == 'request-day') && $currentUser->hasPermission('ohano stats access request day stats')) {
      $statistics[] = [
        '#theme' => 'statistics_row',
        '#row_name' => $this->t('Request Day Statistics'),
        '#statistic_container' => $this->getRequestDayStatistics($stat_type != 'request-day'),
      ];
    }

    if (($stat_type == 'all' || $stat_type == 'request-weekday') && $currentUser->hasPermission('ohano stats access request weekday stats')) {
      $statistics[] = [
        '#theme' => 'statistics_row',
        '#row_name' => $this->t('Request Weekday Statistics'),
        '#statistic_container' => $this->getRequestWeekdayStatistics(),
      ];
    }

    if (($stat_type == 'all' || $stat_type == 'request-month') && $currentUser->hasPermission('ohano stats access request month stats')) {
      $statistics[] = [
        '#theme' => 'statistics_row',
        '#row_name' => $this->t('Request Month Statistics'),
        '#statistic_container' => $this->getRequestMonthStatistics(),
      ];
    }

    if (($stat_type == 'all' || $stat_type == 'request-year') && $currentUser->hasPermission('ohano stats access request year stats')) {
      $statistics[] = [
        '#theme' => 'statistics_row',
        '#row_name' => $this->t('Request Year Statistics'),
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

  /**
   * Displays the user statistics page.
   *
   * @return array
   *   A render array for the user statistics page.
   */
  public function getUserStatistics(): array {
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

  /**
   * Displays the profile statistics page.
   *
   * @return array
   *   A render array for the profile statistics page.
   */
  public function getProfileStatistics(): array {
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

  /**
   * Displays the sub-profile statistics page.
   *
   * @return array
   *   A render array for the sub-profile statistics page.
   */
  public function getSubProfileStatistics(): array {
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

  /**
   * Displays the visited pages statistics page.
   *
   * @param bool $limited
   *   Whether to limit the results.
   *
   * @return array
   *   A render array for the visited pages statistics page.
   */
  public function getVisitedPagesStatistics(bool $limited = TRUE): array {
    $statistics = [];
    $trackerQuery = \Drupal::entityQuery(PathRequest::entityTypeId())
      ->sort('count', 'DESC');
    $trackerResult = $trackerQuery->execute();
    $count = 0;
    foreach ($trackerResult as $pathRequestId) {
      $pathRequest = PathRequest::load($pathRequestId);
      $statistics[] = [
        'name' => $pathRequest->getPath(),
        'value' => $pathRequest->getCount(),
      ];
      if (++$count >= self::LIMIT && $limited) {
        $statistics[] = [
          'link' => Url::fromRoute('ohano_stats.index', ['stat_type' => 'visited-pages'])->toString(),
        ];
        break;
      }
    }

    return [
      '#theme' => 'statistic_container',
      '#statistics' => $statistics,
      '#cache' => [
        'max-age' => 0,
      ],
    ];
  }

  /**
   * Displays the user agent statistics page.
   *
   * @param bool $limited
   *   Whether to limit the results.
   *
   * @return array
   *   A render array for the user agent statistics page.
   */
  public function getUserAgentStatistics(bool $limited = TRUE): array {
    $statistics = [];
    $trackerQuery = \Drupal::entityQuery(UserAgent::entityTypeId())
      ->sort('count', 'DESC');
    $trackerResult = $trackerQuery->execute();
    $count = 0;
    foreach ($trackerResult as $pathRequestId) {
      $pathRequest = UserAgent::load($pathRequestId);
      $statistics[] = [
        'name' => $pathRequest->getUserAgent(),
        'value' => $pathRequest->getCount(),
      ];
      if (++$count >= self::LIMIT && $limited) {
        $statistics[] = [
          'link' => Url::fromRoute('ohano_stats.index', ['stat_type' => 'user-agent'])->toString(),
        ];
        break;
      }
    }

    return [
      '#theme' => 'statistic_container',
      '#statistics' => $statistics,
    ];
  }

  /**
   * Displays the platform statistics page.
   *
   * @param bool $limited
   *   Whether to limit the results.
   *
   * @return array
   *   A render array for the platform statistics page.
   */
  public function getPlatformStatistics(bool $limited = TRUE): array {
    $statistics = [];
    $trackerQuery = \Drupal::entityQuery(Platform::entityTypeId())
      ->sort('count', 'DESC');
    $trackerResult = $trackerQuery->execute();
    $count = 0;
    foreach ($trackerResult as $pathRequestId) {
      $pathRequest = Platform::load($pathRequestId);
      $statistics[] = [
        'name' => $pathRequest->getPlatform(),
        'value' => $pathRequest->getCount(),
      ];
      if (++$count >= self::LIMIT && $limited) {
        $statistics[] = [
          'link' => Url::fromRoute('ohano_stats.index', ['stat_type' => 'client-platform'])->toString(),
        ];
        break;
      }
    }

    return [
      '#theme' => 'statistic_container',
      '#statistics' => $statistics,
    ];
  }

  /**
   * Displays the request time statistics page.
   *
   * @param bool $limited
   *   Whether to limit the results.
   *
   * @return array
   *   A render array for the request time statistics page.
   */
  public function getRequestTimeStatistics(bool $limited = TRUE): array {
    $statistics = [];
    $trackerQuery = \Drupal::entityQuery(RequestTime::entityTypeId())
      ->sort($limited ? 'count' : 'time', $limited ? 'DESC' : 'ASC');
    $trackerResult = $trackerQuery->execute();
    $count = 0;
    foreach ($trackerResult as $pathRequestId) {
      $pathRequest = RequestTime::load($pathRequestId);
      $statistics[] = [
        'name' => $pathRequest->get('time')->value,
        'value' => $pathRequest->getCount(),
      ];
      if (++$count >= self::LIMIT && $limited) {
        $statistics[] = [
          'link' => Url::fromRoute('ohano_stats.index', ['stat_type' => 'request-time'])->toString(),
        ];
        break;
      }
    }

    return [
      '#theme' => 'statistic_container',
      '#statistics' => $statistics,
    ];
  }

  /**
   * Displays the request day statistics page.
   *
   * @param bool $limited
   *   Whether to limit the results.
   *
   * @return array
   *   A render array for the request day statistics page.
   */
  public function getRequestDayStatistics(bool $limited = TRUE): array {
    $statistics = [];
    $trackerQuery = \Drupal::entityQuery(Day::entityTypeId())
      ->sort($limited ? 'count' : 'day', $limited ? 'DESC' : 'ASC');
    $trackerResult = $trackerQuery->execute();
    $count = 0;
    foreach ($trackerResult as $pathRequestId) {
      $pathRequest = Day::load($pathRequestId);
      $statistics[] = [
        'name' => $pathRequest->get('day')->value,
        'value' => $pathRequest->getCount(),
      ];
      if (++$count >= self::LIMIT && $limited) {
        $statistics[] = [
          'link' => Url::fromRoute('ohano_stats.index', ['stat_type' => 'request-day'])->toString(),
        ];
        break;
      }
    }

    return [
      '#theme' => 'statistic_container',
      '#statistics' => $statistics,
    ];
  }

  /**
   * Displays the request weekday statistics page.
   *
   * @return array
   *   A render array for the request weekday statistics page.
   */
  public function getRequestWeekdayStatistics(): array {
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

  /**
   * Displays the request month statistics page.
   *
   * @return array
   *   A render array for the request month statistics page.
   */
  public function getRequestMonthStatistics(): array {
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

  /**
   * Displays the request year statistics page.
   *
   * @return array
   *   A render array for the request year statistics page.
   */
  public function getRequestYearStatistics(): array {
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

  /**
   * Displays the registered user count.
   *
   * @return array
   *   An array for the registered user count.
   */
  public function getRegisteredUserCount(): array {
    return [
      'name' => $this->t('Registered Users'),
      'value' => (string) $this->statisticService->countRegisteredUsers(),
    ];
  }

  /**
   * Displays the daily active user count.
   *
   * @return array
   *   An array for the daily active user count.
   */
  public function getDailyActiveUsers(): array {
    return [
      'name' => $this->t('Daily Active Users'),
      'value' => (string) $this->statisticService->countActiveUsersForPastDay(),
      'after' => $this->t('were active within the past 24 hours'),
    ];
  }

  /**
   * Displays the weekly active user count.
   *
   * @return array
   *   An array for the weekly active user count.
   */
  public function getWeeklyActiveUsers(): array {
    return [
      'name' => $this->t('Weekly Active Users'),
      'value' => (string) $this->statisticService->countActiveUsersForPastWeek(),
      'after' => $this->t('were active within the past 7 days'),
    ];
  }

  /**
   * Displays the monthly active user count.
   *
   * @return array
   *   An array for the monthly active user count.
   */
  public function getMonthlyActiveUsers(): array {
    return [
      'name' => $this->t('Monthly Active Users'),
      'value' => (string) $this->statisticService->countActiveUsersForPastMonth(),
      'after' => $this->t('were active within the past month'),
    ];
  }

  /**
   * Displays the yearly active user count.
   *
   * @return array
   *   An array for the yearly active user count.
   */
  public function getYearlyActiveUsers(): array {
    return [
      'name' => $this->t('Yearly Active Users'),
      'value' => (string) $this->statisticService->countActiveUsersForPastYear(),
      'after' => $this->t('were active within the past year'),
    ];
  }

  /**
   * Gets all user profiles.
   *
   * @return array
   *   An array of user profiles.
   */
  public function getAllUserProfiles(): array {
    return [
      'name' => $this->t('All Profiles'),
      'value' => (string) $this->statisticService->countAllUserProfiles(),
    ];
  }

  /**
   * Gets all personal user profiles.
   *
   * @return array
   *   An array of personal user profiles.
   */
  public function getPersonalUserProfiles(): array {
    return [
      'name' => $this->t('Personal Profiles'),
      'value' => (string) $this->statisticService->countPersonalUserProfiles(),
    ];
  }

  /**
   * Gets all company user profiles.
   *
   * @return array
   *   An array of company user profiles.
   */
  public function getCompanyUserProfiles(): array {
    return [
      'name' => $this->t('Company Profiles'),
      'value' => (string) $this->statisticService->countCompanyUserProfiles(),
    ];
  }

  /**
   * Gets all artist user profiles.
   *
   * @return array
   *   An array of artist user profiles.
   */
  public function getArtistUserProfiles(): array {
    return [
      'name' => $this->t('Artist Profiles'),
      'value' => (string) $this->statisticService->countArtistUserProfiles(),
    ];
  }

  /**
   * Gets all musician user profiles.
   *
   * @return array
   *   An array of musician user profiles.
   */
  public function getMusicianUserProfiles(): array {
    return [
      'name' => $this->t('Musician Profiles'),
      'value' => (string) $this->statisticService->countMusicianUserProfiles(),
    ];
  }

  /**
   * Gets all influencer user profiles.
   *
   * @return array
   *   An array of influencer user profiles.
   */
  public function getInfluencerUserProfiles(): array {
    return [
      'name' => $this->t('Influencer Profiles'),
      'value' => (string) $this->statisticService->countInfluencerUserProfiles(),
    ];
  }

  /**
   * Gets all streamer user profiles.
   *
   * @return array
   *   An array of streamer user profiles.
   */
  public function getStreamerUserProfiles(): array {
    return [
      'name' => $this->t('Streamer Profiles'),
      'value' => (string) $this->statisticService->countStreamerUserProfiles(),
    ];
  }

  /**
   * Gets all sub-profiles.
   *
   * @return array
   *   An array of sub-profiles.
   */
  public function getSubProfiles(): array {
    return [
      'name' => $this->t('All Sub-Profiles'),
      'value' => (string) (
        $this->statisticService->countBaseProfiles() +
        $this->statisticService->countCodingProfiles() +
        $this->statisticService->countGamingProfiles() +
        $this->statisticService->countJobProfiles() +
        $this->statisticService->countRelationshipProfiles() +
        $this->statisticService->countSocialMediaProfiles()
      ),
    ];
  }

  /**
   * Gets all base user profiles.
   *
   * @return array
   *   An array of base user profiles.
   */
  public function getBaseProfiles(): array {
    return [
      'name' => $this->t('Sub-Profiles of type "Base"'),
      'value' => (string) $this->statisticService->countBaseProfiles(),
    ];
  }

  /**
   * Gets all coding user profiles.
   *
   * @return array
   *   An array of coding user profiles.
   */
  public function getCodingProfiles(): array {
    return [
      'name' => $this->t('Sub-Profiles of type "Coding"'),
      'value' => (string) $this->statisticService->countCodingProfiles(),
    ];
  }

  /**
   * Gets all gaming user profiles.
   *
   * @return array
   *   An array of gaming user profiles.
   */
  public function getGamingProfiles(): array {
    return [
      'name' => $this->t('Sub-Profiles of type "Gaming"'),
      'value' => (string) $this->statisticService->countGamingProfiles(),
    ];
  }

  /**
   * Gets all job user profiles.
   *
   * @return array
   *   An array of job user profiles.
   */
  public function getJobProfiles(): array {
    return [
      'name' => $this->t('Sub-Profiles of type "Job"'),
      'value' => (string) $this->statisticService->countJobProfiles(),
    ];
  }

  /**
   * Gets all relationship user profiles.
   *
   * @return array
   *   An array of relationship user profiles.
   */
  public function getRelationshipProfiles(): array {
    return [
      'name' => $this->t('Sub-Profiles of type "Relationship"'),
      'value' => (string) $this->statisticService->countRelationshipProfiles(),
    ];
  }

}
