<?php

namespace Drupal\ohano_profile\Entity;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Session\AccountInterface;
use Drupal\ohano_core\Entity\EntityBase;
use Drupal\taxonomy\Entity\Term;

/**
 * Defines a base class fpr use with sub-profile entities.
 *
 * @package Drupal\ohano_profile\Entity
 */
abstract class SubProfileBase extends EntityBase implements SubProfileInterface {

  /**
   * Sets the field value from taxonomy terms.
   *
   * @param string $fieldName
   *   The field name.
   * @param \Drupal\taxonomy\Entity\Term[]|null $termValues
   *   The term values.
   */
  protected function setTermValues(string $fieldName, array $termValues = NULL) {
    $valuesToSave = [];
    if (!empty($termValues)) {
      foreach ($termValues as $termValue) {
        if ($termValue instanceof Term) {
          $valuesToSave[] = [
            'target_id' => $termValue->id(),
          ];
        }
        elseif (isset($termValue['target_id']) && !empty(Term::load($termValue['target_id']))) {
          $valuesToSave[] = $termValue;
        }
      }
    }
    else {
      $valuesToSave = $termValues;
    }

    $this->set($fieldName, $valuesToSave);
  }

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
   * Loads a sub-profile entity by a user.
   *
   * @param \Drupal\Core\Session\AccountInterface $user
   *   The user to load the sub-profile for.
   *
   * @return \Drupal\ohano_profile\Entity\SubProfileBase|null
   *   The sub-profile entity or NULL if not found.
   */
  public static function loadByUser(AccountInterface $user): ?SubProfileBase {
    $userProfile = UserProfile::loadByUser($user);
    return empty($userProfileId) ? NULL : static::loadByProfile($userProfile);
  }

  /**
   * Loads a sub-profile entity by a user profile entity.
   *
   * @param \Drupal\ohano_profile\Entity\UserProfile $userProfile
   *   The user profile to load the sub-profile for.
   *
   * @return \Drupal\ohano_profile\Entity\SubProfileBase|null
   *   The sub-profile entity or NULL if not found.
   */
  public static function loadByProfile(UserProfile $userProfile): ?SubProfileBase {
    $profileId = \Drupal::entityQuery(static::ENTITY_ID)
      ->condition('profile', $userProfile->id())
      ->execute();
    return empty($profileId) ? NULL : static::load(array_values($profileId)[0]);
  }

}
