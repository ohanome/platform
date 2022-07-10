<?php

namespace Drupal\ohano_tracker\Entity;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\ohano_core\Entity\EntityBase;

/**
 * TrackerEntityBase class for use with all tracker entities.
 *
 * @package Drupal\ohano_tracker\Entity
 */
abstract class TrackerEntityBase extends EntityBase {

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['count'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Count'))
      ->setDescription(t('The count of the tracked entry.'))
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
   * Get the count of the tracked entry.
   *
   * @return int
   *   The count of the tracked entry.
   */
  public function getCount(): int {
    return $this->get('count')->value;
  }

  /**
   * Set the count of the tracked entry.
   *
   * @param int $count
   *   The count of the tracked entry.
   *
   * @return \Drupal\ohano_tracker\Entity\TrackerEntityBase
   *   The called object.
   */
  public function setCount(int $count): TrackerEntityBase {
    $this->set('count', $count);
    return $this;
  }

}
