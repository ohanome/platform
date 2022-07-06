<?php

namespace Drupal\ohano_tracker\Entity;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\ohano_core\Entity\EntityBase;

/**
 * Defines the Weekday entity.
 *
 * @package Drupal\ohano_account\Entity
 *
 * @ContentEntityType(
 *   id = "request_weekday",
 *   label = @Translation("Request weekday"),
 *   base_table = "ohano_tracker_weekday",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *     "created" = "created",
 *     "updated" = "updated",
 *     "weekday" = "weekday",
 *   }
 * )
 */
class Weekday extends TrackerEntityBase {

  const ENTITY_ID = 'request_weekday';

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

    $fields['weekday'] = BaseFieldDefinition::create('list_string')
      ->setLabel(t('Weekday'))
      ->setDescription(t('The weekday of the request.'))
      ->setRequired(TRUE)
      ->setSetting('allowed_values', [
        'monday' => 'Monday',
        'tuesday' => 'Tuesday',
        'wednesday' => 'Wednesday',
        'thursday' => 'Thursday',
        'friday' => 'Friday',
        'saturday' => 'Saturday',
        'sunday' => 'Sunday',
      ])
      ->setDisplayOptions('form', [
        'type' => 'options_select',
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }

  public static function loadByWeekday(\DateTime $weekday): ?Weekday {
    $weekday = $weekday->setTimezone(new \DateTimeZone('UTC'))->format('l');
    /** @var \Drupal\ohano_tracker\Entity\Weekday $entity */
    $entity = self::loadByField('weekday', strtolower($weekday));
    return $entity;
  }

  public static function loadOrCreateByWeekday(\DateTime $weekday): Weekday {
    $weekday = $weekday->setTimezone(new \DateTimeZone('UTC'))->format('l');
    /** @var \Drupal\ohano_tracker\Entity\Weekday $entity */
    $entity = self::loadOrCreateByField('weekday', strtolower($weekday));
    if ($entity->get('count')->value == NULL) {
      $entity->set('count', 0);
    }
    return $entity;
  }

}
