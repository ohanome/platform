<?php

namespace Drupal\ohano_tracker\Entity;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;

/**
 * Defines the Year entity.
 *
 * @package Drupal\ohano_account\Entity
 *
 * @ContentEntityType(
 *   id = "request_year",
 *   label = @Translation("Request year"),
 *   base_table = "ohano_tracker_year",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *     "created" = "created",
 *     "updated" = "updated",
 *     "year" = "year",
 *   }
 * )
 */
class Year extends TrackerEntityBase {

  const ENTITY_ID = 'request_year';

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

    $fields['year'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Year'))
      ->setDescription(t('The year of the request.'))
      ->setRequired(TRUE)
      ->setSetting('max_length', 4)
      ->setDisplayOptions('form', [
        'type' => 'number',
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }

  /**
   * Loads a year entity by its year.
   *
   * @param \DateTime $year
   *   The year.
   *
   * @return \Drupal\ohano_tracker\Entity\Year|null
   *   The year entity or NULL if not found.
   */
  public static function loadByYear(\DateTime $year): ?Year {
    $year = $year->setTimezone(new \DateTimeZone('UTC'))->format('Y');
    /** @var \Drupal\ohano_tracker\Entity\Year $entity */
    $entity = self::loadByField('year', (int) $year);
    return $entity;
  }

  /**
   * Loads or creates a year entity by its year.
   *
   * @param \DateTime $year
   *   The year.
   *
   * @return \Drupal\ohano_tracker\Entity\Year
   *   The year entity.
   */
  public static function loadOrCreateByYear(\DateTime $year): Year {
    $year = $year->setTimezone(new \DateTimeZone('UTC'))->format('Y');
    /** @var \Drupal\ohano_tracker\Entity\Year $entity */
    $entity = self::loadOrCreateByField('year', (int) $year);
    if ($entity->get('count')->value == NULL) {
      $entity->set('count', 0);
    }
    return $entity;
  }

}
