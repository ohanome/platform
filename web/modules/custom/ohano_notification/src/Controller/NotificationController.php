<?php

namespace Drupal\ohano_notification\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Drupal\ohano_account\Entity\Account;
use Drupal\ohano_notification\Entity\Notification;
use Drupal\ohano_profile\Entity\UserProfile;

/**
 * Provides a controller for the notification center.
 */
class NotificationController extends ControllerBase {

  /**
   * Displays the notification center.
   *
   * @return array
   *   A render array representing the notification center.
   */
  public function listNotifications(): array {
    $account = Account::forActive();

    $notifications = Notification::getAllDelivered($account);
    $allNotifications = [];
    foreach ($notifications as $notification) {
      $n = Notification::load($notification);
      $allNotifications[] = [
        '#theme' => 'notification',
        '#link' => Url::fromUri($n->getLink())->setAbsolute(TRUE)->toString(),
        '#content' => $n->getContent(),
        '#type' => strtolower($n->getType()->name),
        '#timestamp' => $n->getCreatedDateTime()->format('d.m.Y H:i:s'),
      ];
    }

    return [
      '#theme' => 'notification_list',
      '#notifications' => $allNotifications,
    ];
  }

  /**
   * Creates dummy notifications.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  private function createDummyNotifications() {
    $account = Account::forActive();
    $activeProfile = $account->getActiveProfile();
    $allProfiles = UserProfile::loadMultipleByUser($account->getUser());
    $randomProfile = $allProfiles[rand(0, (count($allProfiles) - 1))];
    while ($randomProfile->getId() == $activeProfile->getId()) {
      $randomProfile = $allProfiles[rand(0, (count($allProfiles) - 1))];
    }

    /** @var \Drupal\ohano_notification\Service\NotificationService $notificationService */
    $notificationService = \Drupal::service('ohano_notification.notification');
    $notification = $notificationService->createNewFollowerNotification($account, $activeProfile, $randomProfile);
    $notification->save();

    $notificationService->deliverAllForProfile($account, $activeProfile);
  }

}
