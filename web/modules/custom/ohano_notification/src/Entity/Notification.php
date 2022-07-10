<?php

namespace Drupal\ohano_notification\Entity;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Url;
use Drupal\ohano_account\Entity\Account;
use Drupal\ohano_core\Entity\EntityBase;
use Drupal\ohano_notification\Option\NotificationState;
use Drupal\ohano_notification\Option\NotificationType;
use Drupal\ohano_profile\Entity\UserProfile;

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

    $fields['type'] = BaseFieldDefinition::create('list_string')
      ->setSetting('allowed_values', NotificationType::translatableFormOptions())
      ->setDefaultValue(NotificationType::Misc->value);
    $fields['content'] = BaseFieldDefinition::create('string_long');
    $fields['link'] = BaseFieldDefinition::create('uri');
    $fields['state'] = BaseFieldDefinition::create('list_string')
      ->setSetting('allowed_values', NotificationState::translatableFormOptions())
      ->setDefaultValue(NotificationState::Created);
    $fields['account'] = BaseFieldDefinition::create('entity_reference')
      ->setSetting('target_type', Account::ENTITY_ID)
      ->setSetting('handler', 'default');
    $fields['profile'] = BaseFieldDefinition::create('entity_reference')
      ->setSetting('target_type', UserProfile::ENTITY_ID)
      ->setSetting('handler', 'default');

    return $fields;
  }

  /**
   * Gets all notification IDs for the given account.
   *
   * @param \Drupal\ohano_account\Entity\Account $account
   *   The account to get the notifications for.
   *
   * @return array
   *   An array of notifications.
   */
  public static function getAllByAccount(Account $account): array {
    $query = \Drupal::entityQuery(Notification::entityTypeId())
      ->condition('account', $account->getId());
    return $query->execute();
  }

  /**
   * Gets all notification IDs by the given profile and the state.
   *
   * @param \Drupal\ohano_account\Entity\Account $account
   *   The account to get the notifications for.
   * @param \Drupal\ohano_profile\Entity\UserProfile $profile
   *   The profile to get the notifications for.
   * @param \Drupal\ohano_notification\Option\NotificationState $state
   *   The state to get the notifications for.
   *
   * @return array
   *   An array of notifications.
   */
  public static function getAllByState(Account $account, UserProfile $profile, NotificationState $state): array {
    $query = \Drupal::entityQuery(Notification::entityTypeId())
      ->condition('account', $account->getId())
      ->condition('profile', $profile->getId())
      ->condition('state', $state->value);
    return $query->execute();
  }

  /**
   * Gets all delivered notification IDs by the given account.
   *
   * @param \Drupal\ohano_account\Entity\Account $account
   *   The account to get the notifications for.
   *
   * @return array
   *   An array of notifications.
   */
  public static function getAllDelivered(Account $account): array {
    $query = \Drupal::entityQuery(Notification::entityTypeId())
      ->condition('account', $account->getId())
      ->condition('state', NotificationState::Created->value, '!=');
    return $query->execute();
  }

  /**
   * Gets all unread notification IDs.
   *
   * @param \Drupal\ohano_account\Entity\Account $account
   *   The account to get the notifications for.
   *
   * @return array
   *   An array of notifications.
   */
  public static function getAllUnread(Account $account): array {
    $query = \Drupal::entityQuery(Notification::entityTypeId())
      ->condition('account', $account->getId())
      ->condition('state', NotificationState::Created->value, '!=')
      ->condition('state', NotificationState::Read->value, '!=');
    return $query->execute();
  }

  /**
   * Gets all notifications by the given account.
   *
   * @param \Drupal\ohano_account\Entity\Account $account
   *   The account to get the notifications for.
   *
   * @return array
   *   An array of notifications.
   */
  public static function loadAllByAccount(Account $account): array {
    $result = Notification::getAllByAccount($account);
    return Notification::loadMultiple(array_values($result));
  }

  /**
   * Gets all notifications by the given profile and the state.
   *
   * @param \Drupal\ohano_account\Entity\Account $account
   *   The account to get the notifications for.
   * @param \Drupal\ohano_profile\Entity\UserProfile $profile
   *   The profile to get the notifications for.
   * @param \Drupal\ohano_notification\Option\NotificationState $state
   *   The state to get the notifications for.
   *
   * @return array
   *   An array of notifications.
   */
  public static function loadAllByState(Account $account, UserProfile $profile, NotificationState $state): array {
    $result = Notification::getAllByState($account, $profile, $state);
    return Notification::loadMultiple(array_values($result));
  }

  /**
   * Gets all delivered notifications by the given account.
   *
   * @param \Drupal\ohano_account\Entity\Account $account
   *   The account to get the notifications for.
   *
   * @return array
   *   An array of notifications.
   */
  public static function loadAllDelivered(Account $account): array {
    $result = Notification::getAllDelivered($account);
    return Notification::loadMultiple(array_values($result));
  }

  /**
   * Gets all unread notifications by the given account.
   *
   * @param \Drupal\ohano_account\Entity\Account $account
   *   The account to get the notifications for.
   *
   * @return array
   *   An array of notifications.
   */
  public static function loadAllUnread(Account $account): array {
    $result = Notification::getAllUnread($account);
    return Notification::loadMultiple(array_values($result));
  }

  /**
   * Gets the notification type.
   *
   * @return \Drupal\ohano_notification\Option\NotificationType
   *   The notification type.
   */
  public function getType(): NotificationType {
    $value = $this->get('type')->value;
    $value = NotificationType::tryFrom($value);
    if (empty($value)) {
      $value = NotificationType::Misc;
    }

    return $value;
  }

  /**
   * Gets the notification content.
   *
   * @return string
   *   The notification content.
   */
  public function getContent(): string {
    return $this->get('content')->value;
  }

  /**
   * Gets the notification link.
   *
   * @return string
   *   The notification link.
   */
  public function getLink(): string {
    return $this->get('link')->value;
  }

  /**
   * Gets the notification state.
   *
   * @return \Drupal\ohano_notification\Option\NotificationState
   *   The notification state.
   */
  public function getState(): NotificationState {
    $value = $this->get('state')->value;
    $value = NotificationState::tryFrom($value);
    if (empty($value)) {
      $value = NotificationState::Created;
    }

    return $value;
  }

  /**
   * Gets the associated account.
   *
   * @return \Drupal\ohano_account\Entity\Account
   *   The associated account.
   */
  public function getAccount(): Account {
    return $this->get('account')->referencedEntities()[0];
  }

  /**
   * Gets the associated profile.
   *
   * @return \Drupal\ohano_profile\Entity\UserProfile
   *   The associated profile.
   */
  public function getProfile(): UserProfile {
    return $this->get('profile')->referencedEntities()[0];
  }

  /**
   * Sets the notification type.
   *
   * @param \Drupal\ohano_notification\Option\NotificationType $type
   *   The notification type.
   *
   * @return \Drupal\ohano_notification\Entity\Notification
   *   The active instance of this class.
   */
  public function setType(NotificationType $type): Notification {
    $this->set('type', $type->value);
    return $this;
  }

  /**
   * Sets the notification content.
   *
   * @param string $content
   *   The notification content.
   *
   * @return \Drupal\ohano_notification\Entity\Notification
   *   The active instance of this class.
   */
  public function setContent(string $content): Notification {
    $this->set('content', $content);
    return $this;
  }

  /**
   * Sets the notification link.
   *
   * @param \Drupal\Core\Url $link
   *   The notification link.
   *
   * @return \Drupal\ohano_notification\Entity\Notification
   *   The active instance of this class.
   */
  public function setLink(Url $link): Notification {
    $this->set('link', $link->toUriString());
    return $this;
  }

  /**
   * Sets the notification state.
   *
   * @param \Drupal\ohano_notification\Option\NotificationState $state
   *   The notification state.
   *
   * @return \Drupal\ohano_notification\Entity\Notification
   *   The active instance of this class.
   */
  public function setState(NotificationState $state): Notification {
    $this->set('state', $state->value);
    return $this;
  }

  /**
   * Sets the associated account.
   *
   * @param \Drupal\ohano_account\Entity\Account $account
   *   The associated account.
   *
   * @return \Drupal\ohano_notification\Entity\Notification
   *   The active instance of this class.
   */
  public function setAccount(Account $account): Notification {
    $this->set('account', $account->getId());
    return $this;
  }

  /**
   * Sets the associated profile.
   *
   * @param \Drupal\ohano_profile\Entity\UserProfile $profile
   *   The associated profile.
   *
   * @return \Drupal\ohano_notification\Entity\Notification
   *   The active instance of this class.
   */
  public function setProfile(UserProfile $profile): Notification {
    $this->set('profile', $profile->getId());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function render(): array {
    return parent::render() + [
      'account' => $this->getAccount()->render(),
      'profile' => $this->getProfile()->render(),
      'type' => $this->getType(),
      'type_value' => $this->getType()->value,
      'content' => $this->getContent(),
      'link' => $this->getLink(),
      'state' => $this->getState(),
      'state_value' => $this->getState()->value,
    ];
  }

}
