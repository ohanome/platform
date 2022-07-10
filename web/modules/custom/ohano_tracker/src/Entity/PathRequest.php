<?php

namespace Drupal\ohano_tracker\Entity;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;

/**
 * Defines the PathRequest entity.
 *
 * @package Drupal\ohano_account\Entity
 *
 * @ContentEntityType(
 *   id = "path_request",
 *   label = @Translation("Path request"),
 *   base_table = "ohano_tracker_path_request",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *     "created" = "created",
 *     "updated" = "updated",
 *     "count" = "count",
 *     "path" = "path",
 *   }
 * )
 */
class PathRequest extends TrackerEntityBase {

  const ENTITY_ID = 'path_request';

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

    $fields['path'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Path'))
      ->setDescription(t('The path of the request.'))
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
   * Loads a path request entity by its path.
   *
   * @param string $path
   *   The path of the path request entity.
   *
   * @return \Drupal\ohano_tracker\Entity\PathRequest|null
   *   The path request entity or NULL if not found.
   */
  public static function loadByPath(string $path): ?PathRequest {
    /** @var \Drupal\ohano_tracker\Entity\PathRequest $entity */
    $entity = self::loadByField('path', $path);
    return $entity;
  }

  /**
   * Loads or creates a path request entity by its path.
   *
   * @param string $path
   *   The path of the path request entity.
   *
   * @return \Drupal\ohano_tracker\Entity\PathRequest
   *   The path request entity.
   */
  public static function loadOrCreateByPath(string $path): PathRequest {
    /** @var \Drupal\ohano_tracker\Entity\PathRequest $entity */
    $entity = self::loadOrCreateByField('path', $path);
    if ($entity->get('count')->value == NULL) {
      $entity->set('count', 0);
    }
    return $entity;
  }

  /**
   * Gets the path of the request.
   *
   * @return string
   *   The path of the request.
   */
  public function getPath(): string {
    return $this->get('path')->value;
  }

  /**
   * Sets the path of the request.
   *
   * @param string $path
   *   The path of the request.
   *
   * @return $this
   */
  public function setPath(string $path): PathRequest {
    $this->set('path', $path);
    return $this;
  }

}
