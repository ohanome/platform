<?php

namespace Drupal\ohano_notification\Entity;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\ohano_core\Entity\EntityBase;
use Drupal\ohano_notification\Option\NotificationState;
use Drupal\ohano_notification\Option\NotificationType;

/**
 * Defines the Notification entity.
 *
 * @package Drupal\ohano_notification\Entity
 *
 * @ContentEntityType(
 *   id = "notification",
 *   label = @Translation("Notification"),
 *   base_table = "ohano_notification",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *     "created" = "created",
 *     "updated" = "updated",
 *     "type" = "type",
 *     "content" = "content",
 *     "link" = "link",
 *     "state" = "state",
 *     "account" = "account",
 *   }
 * )
 */
class Notification extends EntityBase {

  const ENTITY_ID = 'notification';

  public static function entityTypeId(): string {
    return self::ENTITY_ID;
  }

  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['type'] = BaseFieldDefinition::create('list_string')
      ->setSetting('allowed_values', NotificationType::translatableFormOptions())
      ->setDefaultValue(NotificationType::Security->value);
    $fields['content'] = BaseFieldDefinition::create('string_long');
    $fields['link'] = BaseFieldDefinition::create('uri');
    $fields['state'] = BaseFieldDefinition::create('list_string')
      ->setSetting('allowed_values', NotificationState::translatableFormOptions())
      ->setDefaultValue(NotificationState::Created);
    $fields['account'] = BaseFieldDefinition::create('entity_reference')
      ->setSetting('target_type', 'account')
      ->setSetting('handler', 'default');

    return $fields;
  }

}
