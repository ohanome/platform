<?php

namespace Drupal\ohano_stats\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\ohano_stats\Service\StatisticsService;
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
    \Drupal::messenger()->addMessage('Sample message');
    \Drupal::messenger()->addMessage('Sample message');
    \Drupal::messenger()->addMessage('Sample message');
    \Drupal::messenger()->addMessage('Sample message');
    \Drupal::messenger()->addMessage('Sample message');
    \Drupal::messenger()->addMessage('Sample message');
    \Drupal::messenger()->addMessage('Sample message');
    \Drupal::messenger()->addError('Sample error');
    \Drupal::messenger()->addError('Sample error');
    \Drupal::messenger()->addError('Sample error');
    \Drupal::messenger()->addError('Sample error');
    \Drupal::messenger()->addError('Sample error');
    \Drupal::messenger()->addError('Sample error');
    \Drupal::messenger()->addError('Sample error');
    \Drupal::messenger()->addWarning('Sample warning');
    \Drupal::messenger()->addWarning('Sample warning');
    \Drupal::messenger()->addWarning('Sample warning');
    \Drupal::messenger()->addWarning('Sample warning');
    \Drupal::messenger()->addWarning('Sample warning');
    \Drupal::messenger()->addWarning('Sample warning');
    \Drupal::messenger()->addWarning('Sample warning');
    \Drupal::messenger()->addStatus('Sample status');
    \Drupal::messenger()->addStatus('Sample status');
    \Drupal::messenger()->addStatus('Sample status');
    \Drupal::messenger()->addStatus('Sample status');
    \Drupal::messenger()->addStatus('Sample status');
    \Drupal::messenger()->addStatus('Sample status');
    \Drupal::messenger()->addStatus('Sample status');

    $statistics = [];

    $statistics[] = [
      '#theme' => 'statistics_row',
      '#row_name' => $this->t('User Statistics'),
      '#statistic_container' => $this->getUserStatistics(),
    ];

    $statistics[] = [
      '#theme' => 'statistics_row',
      '#row_name' => $this->t('Profile Statistics'),
      '#statistic_container' => $this->getProfileStatistics(),
    ];

    $statistics[] = [
      '#theme' => 'statistics_row',
      '#row_name' => $this->t('Sub-Profile Statistics'),
      '#statistic_container' => $this->getSubProfileStatistics(),
    ];

    return [
      '#theme' => 'statistics_page',
      '#statistic_rows' => $statistics,
    ];
  }

  public function getUserStatistics() {
    $currentUser = \Drupal::currentUser();
    $statistics = [];

    if ($currentUser->hasPermission('ohano stats access registered user count')) {
      $stat = $this->getRegisteredUserCount();
      unset($stat['#single']);
      $statistics[] = $stat;
    }

    if ($currentUser->hasPermission('ohano stats access daily active user count')) {
      $stat = $this->getDailyActiveUsers();
      unset($stat['#single']);
      $statistics[] = $stat;
    }

    if ($currentUser->hasPermission('ohano stats access weekly active user count')) {
      $stat = $this->getWeeklyActiveUsers();
      unset($stat['#single']);
      $statistics[] = $stat;
    }

    if ($currentUser->hasPermission('ohano stats access monthly active user count')) {
      $stat = $this->getMonthlyActiveUsers();
      unset($stat['#single']);
      $statistics[] = $stat;
    }

    if ($currentUser->hasPermission('ohano stats access yearly active user count')) {
      $stat = $this->getYearlyActiveUsers();
      unset($stat['#single']);
      $statistics[] = $stat;
    }

    return [
      '#theme' => 'statistic_container',
      '#statistics' => $statistics,
    ];
  }

  public function getProfileStatistics() {
    $currentUser = \Drupal::currentUser();
    $statistics = [];

    if ($currentUser->hasPermission('ohano stats access all user profiles count')) {
      $stat = $this->getAllUserProfiles();
      unset($stat['#single']);
      $statistics[] = $stat;
    }

    if ($currentUser->hasPermission('ohano stats access personal user profiles count')) {
      $stat = $this->getPersonalUserProfiles();
      unset($stat['#single']);
      $statistics[] = $stat;
    }

    if ($currentUser->hasPermission('ohano stats access company user profiles count')) {
      $stat = $this->getCompanyUserProfiles();
      unset($stat['#single']);
      $statistics[] = $stat;
    }

    if ($currentUser->hasPermission('ohano stats access artist user profiles count')) {
      $stat = $this->getArtistUserProfiles();
      unset($stat['#single']);
      $statistics[] = $stat;
    }

    if ($currentUser->hasPermission('ohano stats access musician user profiles count')) {
      $stat = $this->getMusicianUserProfiles();
      unset($stat['#single']);
      $statistics[] = $stat;
    }

    if ($currentUser->hasPermission('ohano stats access influencer user profiles count')) {
      $stat = $this->getInfluencerUserProfiles();
      unset($stat['#single']);
      $statistics[] = $stat;
    }

    if ($currentUser->hasPermission('ohano stats access streamer user profiles count')) {
      $stat = $this->getStreamerUserProfiles();
      unset($stat['#single']);
      $statistics[] = $stat;
    }

    return [
      '#theme' => 'statistic_container',
      '#statistics' => $statistics,
    ];
  }

  public function getSubProfileStatistics() {
    $currentUser = \Drupal::currentUser();
    $statistics = [];

    if ($currentUser->hasPermission('ohano stats access all sub profiles count')) {
      $stat = $this->getSubProfiles();
      unset($stat['#single']);
      $statistics[] = $stat;
    }

    if ($currentUser->hasPermission('ohano stats access base sub profiles count')) {
      $stat = $this->getBaseProfiles();
      unset($stat['#single']);
      $statistics[] = $stat;
    }

    if ($currentUser->hasPermission('ohano stats access coding sub profiles count')) {
      $stat = $this->getCodingProfiles();
      unset($stat['#single']);
      $statistics[] = $stat;
    }

    if ($currentUser->hasPermission('ohano stats access gaming sub profiles count')) {
      $stat = $this->getGamingProfiles();
      unset($stat['#single']);
      $statistics[] = $stat;
    }

    if ($currentUser->hasPermission('ohano stats access job sub profiles count')) {
      $stat = $this->getJobProfiles();
      unset($stat['#single']);
      $statistics[] = $stat;
    }

    if ($currentUser->hasPermission('ohano stats access relationship sub profiles count')) {
      $stat = $this->getRelationshipProfiles();
      unset($stat['#single']);
      $statistics[] = $stat;
    }

    return [
      '#theme' => 'statistic_container',
      '#statistics' => $statistics,
    ];
  }

  public function getRegisteredUserCount() {
    return [
      '#theme' => 'statistic',
      '#name' => $this->t('Registered Users'),
      '#value' => (string) $this->statisticService->countRegisteredUsers(),
      '#link' => 'ohano_stats.user.registered',
      '#single' => 1,
    ];
  }

  public function getDailyActiveUsers() {
    return [
      '#theme' => 'statistic',
      '#name' => $this->t('Daily Active Users'),
      '#value' => (string) $this->statisticService->countActiveUsersForPastDay(),
      '#after' => $this->t('were active within the past 24 hours'),
      '#link' => 'ohano_stats.user.daily_active',
      '#single' => 1,
    ];
  }

  public function getWeeklyActiveUsers() {
    return [
      '#theme' => 'statistic',
      '#name' => $this->t('Weekly Active Users'),
      '#value' => (string) $this->statisticService->countActiveUsersForPastWeek(),
      '#after' => $this->t('were active within the past 7 days'),
      '#link' => 'ohano_stats.user.weekly_active',
      '#single' => 1,
    ];
  }

  public function getMonthlyActiveUsers() {
    return [
      '#theme' => 'statistic',
      '#name' => $this->t('Monthly Active Users'),
      '#value' => (string) $this->statisticService->countActiveUsersForPastMonth(),
      '#after' => $this->t('were active within the past month'),
      '#link' => 'ohano_stats.user.monthly_active',
      '#single' => 1,
    ];
  }

  public function getYearlyActiveUsers() {
    return [
      '#theme' => 'statistic',
      '#name' => $this->t('Yearly Active Users'),
      '#value' => (string) $this->statisticService->countActiveUsersForPastYear(),
      '#after' => $this->t('were active within the past year'),
      '#link' => 'ohano_stats.user.yearly_active',
      '#single' => 1,
    ];
  }

  public function getAllUserProfiles() {
    return [
      '#theme' => 'statistic',
      '#name' => $this->t('All Profiles'),
      '#value' => (string) $this->statisticService->countAllUserProfiles(),
      '#single' => 1,
    ];
  }

  public function getPersonalUserProfiles() {
    return [
      '#theme' => 'statistic',
      '#name' => $this->t('Personal Profiles'),
      '#value' => (string) $this->statisticService->countPersonalUserProfiles(),
      '#single' => 1,
    ];
  }

  public function getCompanyUserProfiles() {
    return [
      '#theme' => 'statistic',
      '#name' => $this->t('Company Profiles'),
      '#value' => (string) $this->statisticService->countCompanyUserProfiles(),
      '#single' => 1,
    ];
  }

  public function getArtistUserProfiles() {
    return [
      '#theme' => 'statistic',
      '#name' => $this->t('Artist Profiles'),
      '#value' => (string) $this->statisticService->countArtistUserProfiles(),
      '#single' => 1,
    ];
  }

  public function getMusicianUserProfiles() {
    return [
      '#theme' => 'statistic',
      '#name' => $this->t('Musician Profiles'),
      '#value' => (string) $this->statisticService->countMusicianUserProfiles(),
      '#single' => 1,
    ];
  }

  public function getInfluencerUserProfiles() {
    return [
      '#theme' => 'statistic',
      '#name' => $this->t('Influencer Profiles'),
      '#value' => (string) $this->statisticService->countInfluencerUserProfiles(),
      '#single' => 1,
    ];
  }

  public function getStreamerUserProfiles() {
    return [
      '#theme' => 'statistic',
      '#name' => $this->t('Streamer Profiles'),
      '#value' => (string) $this->statisticService->countStreamerUserProfiles(),
      '#single' => 1,
    ];
  }

  public function getSubProfiles() {
    return [
      '#theme' => 'statistic',
      '#name' => $this->t('All Sub-Profiles'),
      '#value' => (string) ($this->statisticService->countBaseProfiles() + $this->statisticService->countCodingProfiles() + $this->statisticService->countGamingProfiles() + $this->statisticService->countJobProfiles() + $this->statisticService->countRelationshipProfiles() + $this->statisticService->countSocialMediaProfiles()),
      '#single' => 1,
    ];
  }

  public function getBaseProfiles() {
    return [
      '#theme' => 'statistic',
      '#name' => $this->t('Sub-Profiles of type "Base"'),
      '#value' => (string) $this->statisticService->countBaseProfiles(),
      '#single' => 1,
    ];
  }

  public function getCodingProfiles() {
    return [
      '#theme' => 'statistic',
      '#name' => $this->t('Sub-Profiles of type "Coding"'),
      '#value' => (string) $this->statisticService->countCodingProfiles(),
      '#single' => 1,
    ];
  }

  public function getGamingProfiles() {
    return [
      '#theme' => 'statistic',
      '#name' => $this->t('Sub-Profiles of type "Gaming"'),
      '#value' => (string) $this->statisticService->countGamingProfiles(),
      '#single' => 1,
    ];
  }

  public function getJobProfiles() {
    return [
      '#theme' => 'statistic',
      '#name' => $this->t('Sub-Profiles of type "Job"'),
      '#value' => (string) $this->statisticService->countJobProfiles(),
      '#single' => 1,
    ];
  }

  public function getRelationshipProfiles() {
    return [
      '#theme' => 'statistic',
      '#name' => $this->t('Sub-Profiles of type "Relationship"'),
      '#value' => (string) $this->statisticService->countRelationshipProfiles(),
      '#single' => 1,
    ];
  }

}
