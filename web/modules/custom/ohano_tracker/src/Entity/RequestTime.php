<?php

namespace Drupal\ohano_tracker\Entity;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;

/**
 * Defines the RequestTime entity.
 *
 * @package Drupal\ohano_account\Entity
 *
 * @ContentEntityType(
 *   id = "request_time",
 *   label = @Translation("Request time"),
 *   base_table = "ohano_tracker_request_time",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *     "created" = "created",
 *     "updated" = "updated",
 *     "time" = "time",
 *   }
 * )
 */
class RequestTime extends TrackerEntityBase {

  const ENTITY_ID = 'request_time';

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

    $fields['time'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Time'))
      ->setDescription(t('The time of the request.'))
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
   * Loads a request time entity by its time.
   *
   * @param \DateTime $time
   *   The time of the request.
   *
   * @return \Drupal\ohano_tracker\Entity\RequestTime|null
   *   The request time entity or NULL if not found.
   */
  public static function loadByTime(\DateTime $time): ?RequestTime {
    $time = $time->setTimezone(new \DateTimeZone('UTC'))->format('H:i');
    /** @var \Drupal\ohano_tracker\Entity\RequestTime $entity */
    $entity = self::loadByField('time', $time);
    return $entity;
  }

  /**
   * Loads or creates a request time entity by its time.
   *
   * @param \DateTime $time
   *   The time of the request.
   *
   * @return \Drupal\ohano_tracker\Entity\RequestTime
   *   The request time entity.
   */
  public static function loadOrCreateByTime(\DateTime $time): RequestTime {
    $time = $time->setTimezone(new \DateTimeZone('UTC'))->format('H:i');
    /** @var \Drupal\ohano_tracker\Entity\RequestTime $entity */
    $entity = self::loadOrCreateByField('time', $time);
    if ($entity->get('count')->value == NULL) {
      $entity->set('count', 0);
    }
    return $entity;
  }

}
