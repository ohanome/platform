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

class NotificationService {

  use StringTranslationTrait;

  public function addNotification(Account $account, Notification $notification) {

  }

  public function accountHasUnreadNotifications(Account $account): bool {
    return !empty(Notification::getAllUnread($account));
  }

  public function createNotificationSettingsForAccount(Account $account): NotificationSettings {
    $settings = NotificationSettings::forAccount($account);
    if ($settings) {
      return $settings;
    }

    $settings = NotificationSettings::create([
      'account' => $account->getId()
    ]);
    try {
      $settings->save();
    } catch (EntityStorageException $e) {
      \Drupal::logger('ohano_notification')->emergency($e->getMessage());
      \Drupal::messenger()->addError($this->t('Oops, something went wrong!'));
    }

    return $settings;
  }

  public function createNewFollowerNotification(Account $account, UserProfile $follower): Notification {
    $content = t('%profilename is following you!', ['%profilename' => $follower->getProfileName()]);
    $link = Url::fromRoute('ohano_profile.profile.other', ['username' => $follower->getProfileName()]);

    return Notification::create()
      ->setType(NotificationType::Follower)
      ->setContent($content)
      ->setLink($link)
      ->setState(NotificationState::Created)
      ->setAccount($account);
  }

}
