<?php

namespace Drupal\ohano_notification;

use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\Url;
use Drupal\ohano_account\Entity\Account;
use Drupal\ohano_notification\Entity\Notification;
use Drupal\ohano_notification\Option\NotificationState;
use Drupal\ohano_notification\Option\NotificationType;
use Drupal\ohano_profile\Entity\UserProfile;
use Drupal\user\Entity\User;

/**
 * Class providing several static "shortcut" helpers.
 *
 * @package Drupal\ohano_notification
 */
class OhanoNotification {

  public static function buildNotificationMessage(UserProfile $profile, string $message, array $arguments = []) {
    return t("(%profile): $message", ['%profile' => $profile->getProfileName()] + $arguments);
  }

  public static function createNewFollowerNotification(Account $account, UserProfile $profile, UserProfile $follower): Notification {
    $content = OhanoNotification::buildNotificationMessage(
      $profile,
      "%profilename is following you!",
      [
        '%profilename' => $follower->getProfileName()
      ]
    );
    return Notification::create()
      ->setType(NotificationType::Follower)
      ->setContent($content)
      ->setLink($follower->getProfileUrl())
      ->setState(NotificationState::Created)
      ->setAccount($account)
      ->setProfile($profile);
  }

  public static function createNewFollowerRequestNotification(Account $account, UserProfile $profile, UserProfile $follower): Notification {
    $content = OhanoNotification::buildNotificationMessage(
      $profile,
      "%profilename wants to follow you!",
      [
        '%profilename' => $follower->getProfileName()
      ]
    );
    return Notification::create()
      ->setType(NotificationType::Follower)
      ->setContent($content)
      ->setLink($follower->getProfileUrl())
      ->setState(NotificationState::Created)
      ->setAccount($account)
      ->setProfile($profile);
  }

  public static function createNewMessageNotification(Account $account, UserProfile $profile, UserProfile $follower): Notification {
    $content = OhanoNotification::buildNotificationMessage(
      $profile,
      "%profilename has sent you a message!",
      [
        '%profilename' => $follower->getProfileName()
      ]
    );
    return Notification::create()
      ->setType(NotificationType::Follower)
      ->setContent($content)
      // @TODO: Change this once messages are implemented
      ->setLink($profile->getProfileUrl())
      ->setState(NotificationState::Created)
      ->setAccount($account)
      ->setProfile($profile);
  }

  public static function createNewMessageRequestNotification(Account $account, UserProfile $profile, UserProfile $follower): Notification {
    $content = OhanoNotification::buildNotificationMessage(
      $profile,
      "%profilename has sent you a message request!",
      [
        '%profilename' => $follower->getProfileName()
      ]
    );
    return Notification::create()
      ->setType(NotificationType::Follower)
      ->setContent($content)
      // @TODO: Change this once messages are implemented
      ->setLink($profile->getProfileUrl())
      ->setState(NotificationState::Created)
      ->setAccount($account)
      ->setProfile($profile);
  }

}
