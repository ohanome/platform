<?php

namespace Drupal\ohano_notification\Service;

use Drupal\Core\Entity\EntityStorageException;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Url;
use Drupal\ohano_account\Entity\Account;
use Drupal\ohano_notification\Entity\Notification;
use Drupal\ohano_notification\Entity\NotificationSettings;
use Drupal\ohano_notification\Option\NotificationState;
use Drupal\ohano_notification\Option\NotificationType;
use Drupal\ohano_profile\Entity\UserProfile;

/**
 * Provides a service for notifications.
 *
 * @package Drupal\ohano_notification\Service
 */
class NotificationService {

  use StringTranslationTrait;

  /**
   * Adds a notification for an account.
   *
   * @param \Drupal\ohano_account\Entity\Account $account
   *   The account for which to add a notification.
   * @param \Drupal\ohano_notification\Entity\Notification $notification
   *   The notification to add.
   */
  public function addNotification(Account $account, Notification $notification): void {

  }

  /**
   * Checks if an account has unread notifications.
   *
   * @param \Drupal\ohano_account\Entity\Account $account
   *   The account to check.
   *
   * @return bool
   *   TRUE if the account has unread notifications, FALSE otherwise.
   */
  public function accountHasUnreadNotifications(Account $account): bool {
    return !empty(Notification::getAllUnread($account));
  }

  /**
   * Creates a notification settings entity for an account.
   *
   * @param \Drupal\ohano_account\Entity\Account $account
   *   The account for which to create a notification settings entity.
   */
  public function createNotificationSettingsForAccount(Account $account): NotificationSettings {
    $settings = NotificationSettings::forAccount($account);
    if ($settings) {
      return $settings;
    }

    $settings = NotificationSettings::create([
      'account' => $account->getId(),
    ]);
    try {
      $settings->save();
    }
    catch (EntityStorageException $e) {
      \Drupal::logger('ohano_notification')->emergency($e->getMessage());
      \Drupal::messenger()->addError($this->t('Oops, something went wrong!'));
    }

    return $settings;
  }

  /**
   * Creates a "new follower" notification for an account.
   *
   * @param \Drupal\ohano_account\Entity\Account $account
   *   The account for which to create a notification.
   * @param \Drupal\ohano_profile\Entity\UserProfile $profile
   *   The profile of the user.
   * @param \Drupal\ohano_profile\Entity\UserProfile $follower
   *   The profile of the follower.
   *
   * @return \Drupal\ohano_notification\Entity\Notification
   *   The created notification.
   */
  public function createNewFollowerNotification(Account $account, UserProfile $profile, UserProfile $follower): Notification {
    $content = t('@profilename is following you!', ['@profilename' => $follower->getProfileName()]);
    $link = Url::fromRoute('ohano_profile.profile.other', ['username' => $follower->getProfileName()]);

    return Notification::create()
      ->setType(NotificationType::Follower)
      ->setContent($content)
      ->setLink($link)
      ->setState(NotificationState::Created)
      ->setProfile($profile)
      ->setAccount($account);
  }

  /**
   * Delivers a notification to an account.
   *
   * @param \Drupal\ohano_notification\Entity\Notification $notification
   *   The notification to deliver.
   */
  public function deliverNotification(Notification $notification): bool {
    $notification->setState(NotificationState::Delivered);
    try {
      $notification->save();
    }
    catch (EntityStorageException $e) {
      \Drupal::logger('ohano_notification')->critical($e->getMessage());
      \Drupal::messenger()->addError($this->t('Notification could not be delivered.'));
      return FALSE;
    }

    return TRUE;
  }

  /**
   * Delivers all notifications for a profile.
   *
   * @param \Drupal\ohano_account\Entity\Account $account
   *   The account for which to deliver notifications.
   * @param \Drupal\ohano_profile\Entity\UserProfile $profile
   *   The profile for which to deliver notifications.
   */
  public function deliverAllForProfile(Account $account, UserProfile $profile): void {
    $notifications = Notification::loadAllByState($account, $profile, NotificationState::Created);
    if (!empty($notifications)) {
      foreach ($notifications as $notification) {
        $this->deliverNotification($notification);
      }
    }
  }

}
