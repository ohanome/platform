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
use Drupal\ohano_profile\Option\RelationshipType;
use Drupal\user\Entity\User;

class StatisticsService {

  private function getUserQuery($includeUsersWithRoles = FALSE): QueryInterface {
    $query = \Drupal::entityQuery('user')
      ->condition('uid', '0', '!=');

    if (!$includeUsersWithRoles) {
      $query->condition('roles', NULL, 'IS NULL');
      $query->condition('uid', '1', '!=');
    }

    return $query;
  }

  private function countActiveUsersForPastTimespan(string $timespan, $includeUsersWithRoles = FALSE) {
    $query = $this->getUserQuery($includeUsersWithRoles);

    $now = new DrupalDateTime();
    $then = clone($now);
    $then = $then->modify($timespan);

    $then = (int) $then->format('U');

    $query->condition('access', $then, '>');
    $result = $query->execute();
    return count($result);
  }

  public function countRegisteredUsers($includeUsersWithRoles = FALSE) {
    $query = $this->getUserQuery($includeUsersWithRoles);
    $result = $query->execute();
    return count($result);
  }

  public function countActiveUsersForPastDay($includeUsersWithRoles = FALSE) {
    return $this->countActiveUsersForPastTimespan('-1 day', $includeUsersWithRoles);
  }

  public function countActiveUsersForPastWeek($includeUsersWithRoles = FALSE) {
    return $this->countActiveUsersForPastTimespan('-1 week', $includeUsersWithRoles);
  }

  public function countActiveUsersForPastMonth($includeUsersWithRoles = FALSE) {
    return $this->countActiveUsersForPastTimespan('-1 month', $includeUsersWithRoles);
  }

  public function countActiveUsersForPastYear($includeUsersWithRoles = FALSE) {
    return $this->countActiveUsersForPastTimespan('-1 year', $includeUsersWithRoles);
  }

  public function countAllUserProfiles() {
    $query = \Drupal::entityQuery(UserProfile::entityTypeId());
    return count($query->execute());
  }

  public function countPersonalUserProfiles() {
    $query = \Drupal::entityQuery(UserProfile::entityTypeId())
      ->condition('type', ProfileType::Personal->value);
    return count($query->execute());
  }

  public function countCompanyUserProfiles() {
    $query = \Drupal::entityQuery(UserProfile::entityTypeId())
      ->condition('type', ProfileType::Company->value);
    return count($query->execute());
  }

  public function countArtistUserProfiles() {
    $query = \Drupal::entityQuery(UserProfile::entityTypeId())
      ->condition('type', ProfileType::Artist->value);
    return count($query->execute());
  }

  public function countMusicianUserProfiles() {
    $query = \Drupal::entityQuery(UserProfile::entityTypeId())
      ->condition('type', ProfileType::Musician->value);
    return count($query->execute());
  }

  public function countInfluencerUserProfiles() {
    $query = \Drupal::entityQuery(UserProfile::entityTypeId())
      ->condition('type', ProfileType::Influencer->value);
    return count($query->execute());
  }

  public function countStreamerUserProfiles() {
    $query = \Drupal::entityQuery(UserProfile::entityTypeId())
      ->condition('type', ProfileType::Streamer->value);
    return count($query->execute());
  }

  public function countBaseProfiles() {
    $query = \Drupal::entityQuery(BaseProfile::entityTypeId());
    return count($query->execute());
  }

  public function countCodingProfiles() {
    $query = \Drupal::entityQuery(CodingProfile::entityTypeId());
    return count($query->execute());
  }

  public function countGamingProfiles() {
    $query = \Drupal::entityQuery(GamingProfile::entityTypeId());
    return count($query->execute());
  }

  public function countJobProfiles() {
    $query = \Drupal::entityQuery(JobProfile::entityTypeId());
    return count($query->execute());
  }

  public function countRelationshipProfiles() {
    $query = \Drupal::entityQuery(RelationshipProfile::entityTypeId());
    return count($query->execute());
  }

  public function countSocialMediaProfiles() {
    $query = \Drupal::entityQuery(SocialMediaProfile::entityTypeId());
    return count($query->execute());
  }

}
