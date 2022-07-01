<?php

namespace Drupal\ohano_tracker\Entity;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\ohano_core\Entity\EntityBase;

/**
 * Defines the PathRequest entity.
 *
 * @package Drupal\ohano_account\Entity
 *
 * @ContentEntityType(
 *   id = "path_request",
 *   label = @Translation("Account"),
 *   base_table = "ohano_path_request",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *     "created" = "created",
 *     "updated" = "updated",
 *     "path" = "path",
 *   }
 * )
 */
class PathRequest extends EntityBase {

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
