<?php

namespace Drupal\ohano_tracker\Entity;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;

/**
 * Defines the UserAgent entity.
 *
 * @package Drupal\ohano_account\Entity
 *
 * @ContentEntityType(
 *   id = "user_agent",
 *   label = @Translation("User agent"),
 *   base_table = "ohano_tracker_user_agent",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *     "created" = "created",
 *     "updated" = "updated",
 *     "count" = "count",
 *     "user_agent" = "user_agent",
 *   }
 * )
 */
class UserAgent extends TrackerEntityBase {

  const ENTITY_ID = 'user_agent';

  /**
   * {@inheritdoc}
   */
  public static function entityTypeId(): string {
    return self::ENTITY_ID;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['user_agent'] = BaseFieldDefinition::create('string')
      ->setLabel(t('User agent'))
      ->setDescription(t('The user agent of the request.'))
      ->setRequired(TRUE)
      ->setSetting('max_length', 255)
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }

  /**
   * Loads a user agent entity by its user agent.
   *
   * @param string $userAgent
   *   The user agent of the request.
   *
   * @return \Drupal\ohano_tracker\Entity\UserAgent|null
   *   The user agent entity or NULL if not found.
   */
  public static function loadByUserAgent(string $userAgent): ?UserAgent {
    /** @var \Drupal\ohano_tracker\Entity\UserAgent $entity */
    $entity = self::loadByField('user_agent', $userAgent);
    return $entity;
  }

  /**
   * Loads or creates a user agent entity by its user agent.
   *
   * @param string $userAgent
   *   The user agent of the request.
   *
   * @return \Drupal\ohano_tracker\Entity\UserAgent
   *   The user agent entity.
   */
  public static function loadOrCreateByUserAgent(string $userAgent): UserAgent {
    /** @var \Drupal\ohano_tracker\Entity\UserAgent $entity */
    $entity = self::loadOrCreateByField('user_agent', $userAgent);
    if ($entity->get('count')->value == NULL) {
      $entity->set('count', 0);
    }
    return $entity;
  }

  /**
   * Gets the user agent of the request.
   */
  public function getUserAgent(): string {
    return $this->get('user_agent')->value;
  }

  /**
   * Sets the user agent of the request.
   */
  public function setUserAgent(string $user_agent): UserAgent {
    $this->set('user_agent', $user_agent);
    return $this;
  }

}
