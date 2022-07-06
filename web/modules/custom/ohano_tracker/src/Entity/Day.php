<?php

namespace Drupal\ohano_tracker\Entity;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;

/**
 * Defines the Day entity.
 *
 * @package Drupal\ohano_account\Entity
 *
 * @ContentEntityType(
 *   id = "request_day",
 *   label = @Translation("Request day"),
 *   base_table = "ohano_tracker_day",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *     "created" = "created",
 *     "updated" = "updated",
 *     "day" = "day",
 *   }
 * )
 */
class Day extends TrackerEntityBase {

  const ENTITY_ID = 'request_day';

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

    $fields['day'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Day'))
      ->setDescription(t('The day of the request.'))
      ->setRequired(TRUE)
      ->setSetting('max_length', 6)
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }

  public static function loadByDay(\DateTime $day): ?Day {
    $day = $day->setTimezone(new \DateTimeZone('UTC'))->format('m/d');
    /** @var \Drupal\ohano_tracker\Entity\Day $entity */
    $entity = self::loadByField('day', $day);
    return $entity;
  }

  public static function loadOrCreateByDay(\DateTime $day): Day {
    $day = $day->setTimezone(new \DateTimeZone('UTC'))->format('m/d');
    /** @var \Drupal\ohano_tracker\Entity\Day $entity */
    $entity = self::loadOrCreateByField('day', $day);
    if ($entity->get('count')->value == NULL) {
      $entity->set('count', 0);
    }
    return $entity;
  }

}
