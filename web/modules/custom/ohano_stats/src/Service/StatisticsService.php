<?php

namespace Drupal\ohano_stats\Service;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Entity\Query\QueryInterface;
use Drupal\ohano_profile\Entity\BaseProfile;
use Drupal\ohano_profile\Entity\CodingProfile;
use Drupal\ohano_profile\Entity\GamingProfile;
use Drupal\ohano_profile\Entity\JobProfile;
use Drupal\ohano_profile\Entity\RelationshipProfile;
use Drupal\ohano_profile\Entity\SocialMediaProfile;
use Drupal\ohano_profile\Entity\UserProfile;
use Drupal\ohano_profile\Option\ProfileType;

/**
 * Provides a service for statistics.
 */
class StatisticsService {

  /**
   * Gets the user query.
   *
   * @param bool $includeUsersWithRoles
   *   Whether to include users with roles.
   *
   * @return \Drupal\Core\Entity\Query\QueryInterface
   *   The user query.
   */
  private function getUserQuery(bool $includeUsersWithRoles = FALSE): QueryInterface {
    $query = \Drupal::entityQuery('user')
      ->condition('uid', '0', '!=');

    if (!$includeUsersWithRoles) {
      $query->condition('roles', NULL, 'IS NULL');
      $query->condition('uid', '1', '!=');
    }

    return $query;
  }

  /**
   * Counts the number of active user for the given time period.
   *
   * @param string $timespan
   *   The time period.
   * @param bool $includeUsersWithRoles
   *   Whether to include users with roles.
   *
   * @return int
   *   The number of active users.
   */
  private function countActiveUsersForPastTimespan(string $timespan, bool $includeUsersWithRoles = FALSE): int {
    $query = $this->getUserQuery($includeUsersWithRoles);

    $now = new DrupalDateTime();
    $then = clone($now);
    $then = $then->modify($timespan);

    $then = (int) $then->format('U');

    $query->condition('access', $then, '>');
    $result = $query->execute();
    return count($result);
  }

  /**
   * Counts the number of registered users.
   *
   * @param bool $includeUsersWithRoles
   *   Whether to include users with roles.
   *
   * @return int
   *   The number of registered users.
   */
  public function countRegisteredUsers(bool $includeUsersWithRoles = FALSE): int {
    $query = $this->getUserQuery($includeUsersWithRoles);
    $result = $query->execute();
    return count($result);
  }

  /**
   * Counts the number of active users for the past day.
   *
   * @param bool $includeUsersWithRoles
   *   Whether to include users with roles.
   *
   * @return int
   *   The number of active users.
   */
  public function countActiveUsersForPastDay(bool $includeUsersWithRoles = FALSE): int {
    return $this->countActiveUsersForPastTimespan('-1 day', $includeUsersWithRoles);
  }

  /**
   * Counts the number of active users for the past week.
   *
   * @param bool $includeUsersWithRoles
   *   Whether to include users with roles.
   *
   * @return int
   *   The number of active users.
   */
  public function countActiveUsersForPastWeek(bool $includeUsersWithRoles = FALSE): int {
    return $this->countActiveUsersForPastTimespan('-1 week', $includeUsersWithRoles);
  }

  /**
   * Counts the number of active users for the past month.
   *
   * @param bool $includeUsersWithRoles
   *   Whether to include users with roles.
   *
   * @return int
   *   The number of active users.
   */
  public function countActiveUsersForPastMonth(bool $includeUsersWithRoles = FALSE): int {
    return $this->countActiveUsersForPastTimespan('-1 month', $includeUsersWithRoles);
  }

  /**
   * Counts the number of active users for the past year.
   *
   * @param bool $includeUsersWithRoles
   *   Whether to include users with roles.
   *
   * @return int
   *   The number of active users.
   */
  public function countActiveUsersForPastYear(bool $includeUsersWithRoles = FALSE): int {
    return $this->countActiveUsersForPastTimespan('-1 year', $includeUsersWithRoles);
  }

  /**
   * Counts the number of all user profiles.
   *
   * @return int
   *   The number of user profiles.
   */
  public function countAllUserProfiles(): int {
    $query = \Drupal::entityQuery(UserProfile::entityTypeId());
    return count($query->execute());
  }

  /**
   * Counts the number of personal user profiles.
   *
   * @return int
   *   The number of personal profiles.
   */
  public function countPersonalUserProfiles(): int {
    $query = \Drupal::entityQuery(UserProfile::entityTypeId())
      ->condition('type', ProfileType::Personal->value);
    return count($query->execute());
  }

  /**
   * Counts the number of company user profiles.
   *
   * @return int
   *   The number of company profiles.
   */
  public function countCompanyUserProfiles(): int {
    $query = \Drupal::entityQuery(UserProfile::entityTypeId())
      ->condition('type', ProfileType::Company->value);
    return count($query->execute());
  }

  /**
   * Counts the number of artist user profiles.
   *
   * @return int
   *   The number of artist profiles.
   */
  public function countArtistUserProfiles(): int {
    $query = \Drupal::entityQuery(UserProfile::entityTypeId())
      ->condition('type', ProfileType::Artist->value);
    return count($query->execute());
  }

  /**
   * Counts the number of musician user profiles.
   *
   * @return int
   *   The number of musician profiles.
   */
  public function countMusicianUserProfiles(): int {
    $query = \Drupal::entityQuery(UserProfile::entityTypeId())
      ->condition('type', ProfileType::Musician->value);
    return count($query->execute());
  }

  /**
   * Counts the number of influencer user profiles.
   *
   * @return int
   *   The number of influencer profiles.
   */
  public function countInfluencerUserProfiles(): int {
    $query = \Drupal::entityQuery(UserProfile::entityTypeId())
      ->condition('type', ProfileType::Influencer->value);
    return count($query->execute());
  }

  /**
   * Counts the number of streamer user profiles.
   *
   * @return int
   *   The number of streamer profiles.
   */
  public function countStreamerUserProfiles(): int {
    $query = \Drupal::entityQuery(UserProfile::entityTypeId())
      ->condition('type', ProfileType::Streamer->value);
    return count($query->execute());
  }

  /**
   * Counts the number of base profiles.
   *
   * @return int
   *   The number of base profiles.
   */
  public function countBaseProfiles(): int {
    $query = \Drupal::entityQuery(BaseProfile::entityTypeId());
    return count($query->execute());
  }

  /**
   * Counts the number of coding profiles.
   *
   * @return int
   *   The number of coding profiles.
   */
  public function countCodingProfiles(): int {
    $query = \Drupal::entityQuery(CodingProfile::entityTypeId());
    return count($query->execute());
  }

  /**
   * Counts the number of gaming profiles.
   *
   * @return int
   *   The number of gaming profiles.
   */
  public function countGamingProfiles(): int {
    $query = \Drupal::entityQuery(GamingProfile::entityTypeId());
    return count($query->execute());
  }

  /**
   * Counts the number of job profiles.
   *
   * @return int
   *   The number of job profiles.
   */
  public function countJobProfiles(): int {
    $query = \Drupal::entityQuery(JobProfile::entityTypeId());
    return count($query->execute());
  }

  /**
   * Counts the number of relationship profiles.
   *
   * @return int
   *   The number of relationship profiles.
   */
  public function countRelationshipProfiles(): int {
    $query = \Drupal::entityQuery(RelationshipProfile::entityTypeId());
    return count($query->execute());
  }

  /**
   * Counts the number of social media profiles.
   *
   * @return int
   *   The number of social media profiles.
   */
  public function countSocialMediaProfiles(): int {
    $query = \Drupal::entityQuery(SocialMediaProfile::entityTypeId());
    return count($query->execute());
  }

}
