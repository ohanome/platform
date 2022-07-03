<?php

namespace Drupal\ohano_tracker\Entity;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;

/**
 * Defines the Platform entity.
 *
 * @package Drupal\ohano_account\Entity
 *
 * @ContentEntityType(
 *   id = "platform",
 *   label = @Translation("Platform (Sec-CH-UA Platform)"),
 *   base_table = "ohano_tracker_platform",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *     "created" = "created",
 *     "updated" = "updated",
 *     "count" = "count",
 *     "platform" = "platform",
 *   }
 * )
 */
class Platform extends TrackerEntityBase {

  const ENTITY_ID = 'platform';

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

    $fields['platform'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Platform'))
      ->setDescription(t('The platform of the request.'))
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

  public static function loadByPlatform($platform): ?Platform {
    /** @var \Drupal\ohano_tracker\Entity\Platform $entity */
    $entity = self::loadByField('platform', $platform);
    return $entity;
  }

  public static function loadOrCreateByPlatform($platform): Platform {
    /** @var \Drupal\ohano_tracker\Entity\Platform $entity */
    $entity = self::loadOrCreateByField('platform', $platform);
    if ($entity->get('count')->value == NULL) {
      $entity->set('count', 0);
    }
    return $entity;
  }

  /**
   * Get the platform of the request.
   *
   * @return string
   *   The platform of the request.
   */
  public function getPlatform(): string {
    return $this->get('platform')->value;
  }

  /**
   * Sets the platform of the request.
   *
   * @param string $platform
   *   The platform of the request.
   *
   * @return \Drupal\ohano_tracker\Entity\Platform
   *   The platform of the request.
   */
  public function setPlatform(string $platform): Platform {
    $this->set('platform', $platform);
    return $this;
  }

}
