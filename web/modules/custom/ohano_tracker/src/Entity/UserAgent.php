<?php

namespace Drupal\ohano_tracker\Entity;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;

/*
 * Defines the PathRequest entity.
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

  public static function loadByUserAgent($userAgent): ?UserAgent {
    /** @var \Drupal\ohano_tracker\Entity\UserAgent $entity */
    $entity = self::loadByField('user_agent', $userAgent);
    return $entity;
  }

  public static function loadOrCreateByUserAgent($userAgent): UserAgent {
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
