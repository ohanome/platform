<?php

namespace Drupal\ohano_notification\Entity;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\ohano_account\Entity\Account;
use Drupal\ohano_core\Entity\EntityBase;
use Drupal\ohano_notification\Option\NotificationChannel;

/**
 * Defines the NotificationSettings entity.
 *
 * @package Drupal\ohano_notification\Entity
 *
 * @ContentEntityType(
 *   id = "notification_settings",
 *   label = @Translation("Notification"),
 *   base_table = "ohano_notification_settings",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *     "created" = "created",
 *     "updated" = "updated",
 *     "account" = "account",
 *     "newsletter" = "newsletter",
 *     "follower" = "follower",
 *     "message" = "message",
 *     "security" = "security",
 *     "post" = "post",
 *     "comment" = "comment",
 *     "miscellaneous" = "miscellaneous",
 *   }
 * )
 */
class NotificationSettings extends EntityBase {

  const ENTITY_ID = 'notification_settings';

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

    $fields['account'] = BaseFieldDefinition::create('entity_reference')
      ->setSetting('target_type', 'account')
      ->setSetting('handler', 'default');
    $fields['newsletter'] = BaseFieldDefinition::create('list_string')
      ->setSetting('allowed_values', NotificationChannel::translatableFormOptions())
      ->setDefaultValue(NotificationChannel::None->value);
    $fields['follower'] = BaseFieldDefinition::create('list_string')
      ->setSetting('allowed_values', NotificationChannel::translatableFormOptions())
      ->setDefaultValue(NotificationChannel::None->value);
    $fields['message'] = BaseFieldDefinition::create('list_string')
      ->setSetting('allowed_values', NotificationChannel::translatableFormOptions())
      ->setDefaultValue(NotificationChannel::None->value);
    $fields['security'] = BaseFieldDefinition::create('list_string')
      ->setSetting('allowed_values', NotificationChannel::translatableFormOptions())
      ->setDefaultValue(NotificationChannel::None->value);
    $fields['post'] = BaseFieldDefinition::create('list_string')
      ->setSetting('allowed_values', NotificationChannel::translatableFormOptions())
      ->setDefaultValue(NotificationChannel::None->value);
    $fields['miscellaneous'] = BaseFieldDefinition::create('list_string')
      ->setSetting('allowed_values', NotificationChannel::translatableFormOptions())
      ->setDefaultValue(NotificationChannel::None->value);
    $fields['comment'] = BaseFieldDefinition::create('list_string')
      ->setSetting('allowed_values', NotificationChannel::translatableFormOptions())
      ->setDefaultValue(NotificationChannel::None->value);

    return $fields;
  }

  /**
   * Gets the notification settings for the given account.
   *
   * @param \Drupal\ohano_account\Entity\Account $account
   *   The account to get the notification settings for.
   *
   * @return \Drupal\ohano_notification\Entity\NotificationSettings|null
   *   The notification settings for the given account or NULL if none exist.
   */
  public static function forAccount(Account $account): ?NotificationSettings {
    $query = \Drupal::entityQuery(self::entityTypeId())
      ->condition('account', $account->getId())
      ->execute();

    if (empty($query)) {
      return NULL;
    }

    $settings = NotificationSettings::load(array_values($query)[0]);
    if (empty($settings)) {
      return NULL;
    }

    return $settings;
  }

}
