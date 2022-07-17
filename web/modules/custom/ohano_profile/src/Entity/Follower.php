<?php

namespace Drupal\ohano_profile\Entity;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\ohano_core\Entity\EntityBase;

/**
 * Defines the Follower entity.
 *
 * Note on the fields: "follower" is the profile that follows "followed".
 *
 * @package Drupal\ohano_profile\Entity\Profile
 *
 * @noinspection PhpUnused
 *
 * @ContentEntityType(
 *   id = "follower",
 *   label = @Translation("Follower"),
 *   base_table = "ohano_follower",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *     "created" = "created",
 *     "updated" = "updated",
 *     "follower" = "follower",
 *     "followed" = "followed",
 *   }
 * )
 */
class Follower extends EntityBase {

  const ENTITY_TYPE = 'follower';

  /**
   * {@inheritdoc}
   */
  public static function entityTypeId(): string {
    return self::ENTITY_TYPE;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['follower'] = BaseFieldDefinition::create('entity_reference')
      ->setSetting('target_type', UserProfile::entityTypeId())
      ->setCardinality(1);

    $fields['followed'] = BaseFieldDefinition::create('entity_reference')
      ->setSetting('target_type', UserProfile::entityTypeId())
      ->setCardinality(1);

    return $fields;
  }

  /**
   * Creates a follower entity for the given profile.
   *
   * @param \Drupal\ohano_profile\Entity\UserProfile $profile
   *   The profile to follow.
   * @param \Drupal\ohano_profile\Entity\UserProfile $follower
   *   The profile that follows the profile.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public static function add(UserProfile $profile, UserProfile $follower) {
    $follower_entity = self::create([
      'follower' => $follower->id(),
      'followed' => $profile->id(),
    ]);
    $follower_entity->save();
  }

  /**
   * Removes a follower entity for the given profile.
   *
   * @param \Drupal\ohano_profile\Entity\UserProfile $profile
   *   The profile to unfollow.
   * @param \Drupal\ohano_profile\Entity\UserProfile $follower
   *   The profile that unfollows the profile.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public static function remove(UserProfile $profile, UserProfile $follower) {
    $follower_entity = self::loadByProfileAndFollower($profile, $follower);
    $follower_entity?->delete();
  }

  /**
   * Gets follower entity ids for the given profile.
   *
   * @param \Drupal\ohano_profile\Entity\UserProfile $profile
   *   The profile to get followers for.
   *
   * @return array
   *   The follower entity ids.
   */
  public static function getByProfile(UserProfile $profile): array {
    $query = \Drupal::entityQuery(self::ENTITY_TYPE);
    $query->condition('followed', $profile->id());
    return $query->execute();
  }

  /**
   * Loads follower entities for the given profile.
   *
   * @param \Drupal\ohano_profile\Entity\UserProfile $profile
   *   The profile to get followers for.
   *
   * @return array
   *   The follower entities.
   */
  public static function loadByProfile(UserProfile $profile): array {
    return self::loadMultiple(self::getByProfile($profile));
  }

  /**
   * Gets following entity ids for the given profile.
   *
   * @param \Drupal\ohano_profile\Entity\UserProfile $follower
   *   The profile to get following for.
   *
   * @return array
   *   The following entity ids.
   */
  public static function getByFollower(UserProfile $follower): array {
    $query = \Drupal::entityQuery(self::ENTITY_TYPE);
    $query->condition('follower', $follower->id());
    return $query->execute();
  }

  /**
   * Gets entity ids for the given profile and follower.
   *
   * @param \Drupal\ohano_profile\Entity\UserProfile $profile
   *   The profile to use.
   * @param \Drupal\ohano_profile\Entity\UserProfile $follower
   *   The profile to match against $profile.
   *
   * @return array|int
   *   The entity id or empty array if not found.
   */
  public static function getByProfileAndFollower(UserProfile $profile, UserProfile $follower): array|int {
    $query = \Drupal::entityQuery(self::ENTITY_TYPE);
    $query->condition('followed', $profile->id());
    $query->condition('follower', $follower->id());
    return $query->execute();
  }

  /**
   * Gets entity ids for the given profile and follower.
   *
   * @param \Drupal\ohano_profile\Entity\UserProfile $profile
   *   The profile to use.
   * @param \Drupal\ohano_profile\Entity\UserProfile $follower
   *   The profile to match against $profile.
   *
   * @return \Drupal\ohano_profile\Entity\Follower|null
   *   The entity or null if not found.
   */
  public static function loadByProfileAndFollower(UserProfile $profile, UserProfile $follower): ?Follower {
    return self::load(self::getByProfileAndFollower($profile, $follower));
  }

  /**
   * Loads following profiles by follower.
   *
   * @param \Drupal\ohano_profile\Entity\UserProfile $follower
   *   The follower profile to use.
   *
   * @return array
   *   The following entities.
   */
  public static function loadByFollower(UserProfile $follower): array {
    return self::loadMultiple(self::getByFollower($follower));
  }

  /**
   * Checks if the given profile is followed by the given follower.
   *
   * @param \Drupal\ohano_profile\Entity\UserProfile $profile
   *   The profile to check.
   * @param \Drupal\ohano_profile\Entity\UserProfile $follower
   *   The follower profile to check.
   *
   * @return bool
   *   True if the profile is followed by the follower.
   */
  public static function isFollower(UserProfile $profile, UserProfile $follower): bool {
    $query = \Drupal::entityQuery(self::ENTITY_TYPE);
    $query->condition('followed', $profile->id());
    $query->condition('follower', $follower->id());
    return $query->execute() !== [];
  }

  /**
   * Gets the follower profile.
   *
   * @return \Drupal\ohano_profile\Entity\UserProfile
   *   The follower profile.
   */
  public function getFollower(): UserProfile {
    return $this->get('follower')->entity;
  }

  /**
   * Gets the followed profile.
   *
   * @return \Drupal\ohano_profile\Entity\UserProfile
   *   The followed profile.
   */
  public function getFollowed(): UserProfile {
    return $this->get('followed')->entity;
  }

  /**
   * Sets the follower profile.
   *
   * @param \Drupal\ohano_profile\Entity\UserProfile $follower
   *   The follower profile.
   *
   * @return \Drupal\ohano_profile\Entity\Follower
   *   The current instance.
   */
  public function setFollower(UserProfile $follower): Follower {
    $this->set('follower', $follower);
    return $this;
  }

  /**
   * Sets the followed profile.
   *
   * @param \Drupal\ohano_profile\Entity\UserProfile $followed
   *   The followed profile.
   *
   * @return \Drupal\ohano_profile\Entity\Follower
   *   The current instance.
   */
  public function setFollowed(UserProfile $followed): Follower {
    $this->set('followed', $followed);
    return $this;
  }

}
