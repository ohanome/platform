<?php

namespace Drupal\ohano_profile\Entity;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Session\AccountInterface;
use Drupal\ohano_core\Entity\EntityBase;
use phpDocumentor\Reflection\Types\Static_;

abstract class SubProfileBase extends EntityBase {

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['profile'] = BaseFieldDefinition::create('entity_reference')
      ->setSetting('target_type', 'user_profile')
      ->setSetting('handler', 'default')
      ->setRequired(TRUE)
      ->setCardinality(1);

    return $fields;
  }

  /**
   * Gets the parent user_profile entity.
   *
   * @return \Drupal\ohano_profile\Entity\UserProfile
   *   The parent profile entity.
   */
  public function getProfile(): UserProfile {
    return $this->get('profile')->referencedEntities()[0];
  }

  /**
   * Connects this entity to a user_profile entity.
   *
   * @param \Drupal\ohano_profile\Entity\UserProfile $profile
   *   The profile to connect with.
   *
   * @return \Drupal\ohano_profile\Entity\SubProfileBase
   *   The active instance of this class.
   */
  public function setProfile(UserProfile $profile): SubProfileBase {
    $this->set('profile', $profile);
    return $this;
  }

  /**
   * @param \Drupal\Core\Session\AccountInterface $user
   *
   * @return \Drupal\ohano_profile\Entity\SubProfileBase|null
   */
  public static function loadByUser(AccountInterface $user): ?SubProfileBase {
    $userProfile = UserProfile::loadByUser($user);
    return empty($userProfileId) ? NULL : static::loadByProfile($userProfile);
  }

  /**
   * @param \Drupal\ohano_profile\Entity\UserProfile $userProfile
   *
   * @return \Drupal\ohano_profile\Entity\SubProfileBase|null
   */
  public static function loadByProfile(UserProfile $userProfile): ?SubProfileBase {
    $profileId = \Drupal::entityQuery(static::ENTITY_ID)
      ->condition('profile', $userProfile->id())
      ->execute();
    return empty($profileId) ? NULL : static::load(array_values($profileId)[0]);
  }
}
