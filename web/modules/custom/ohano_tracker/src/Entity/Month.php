<?php

namespace Drupal\ohano_tracker\Entity;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;

/**
 * Defines the Month entity.
 *
 * @package Drupal\ohano_account\Entity
 *
 * @ContentEntityType(
 *   id = "request_month",
 *   label = @Translation("Request month"),
 *   base_table = "ohano_tracker_month",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *     "created" = "created",
 *     "updated" = "updated",
 *     "month" = "month",
 *   }
 * )
 */
class Month extends TrackerEntityBase {

  const ENTITY_ID = 'request_month';

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

    $fields['month'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Month'))
      ->setDescription(t('The month of the request.'))
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

  /**
   * Loads a month entity by its month.
   *
   * @param \DateTime $month
   *   The month.
   *
   * @return \Drupal\ohano_tracker\Entity\Month|null
   *   The month entity or NULL if not found.
   */
  public static function loadByMonth(\DateTime $month): ?Month {
    $month = $month->setTimezone(new \DateTimeZone('UTC'))->format('F');
    /** @var \Drupal\ohano_tracker\Entity\Month $entity */
    $entity = self::loadByField('month', strtolower($month));
    return $entity;
  }

  /**
   * Loads or creates a month entity by its month.
   *
   * @param \DateTime $month
   *   The month.
   *
   * @return \Drupal\ohano_tracker\Entity\Month
   *   The month entity.
   */
  public static function loadOrCreateByMonth(\DateTime $month): Month {
    $month = $month->setTimezone(new \DateTimeZone('UTC'))->format('F');
    /** @var \Drupal\ohano_tracker\Entity\Month $entity */
    $entity = self::loadOrCreateByField('month', strtolower($month));
    if ($entity->get('count')->value == NULL) {
      $entity->set('count', 0);
    }
    return $entity;
  }

}
