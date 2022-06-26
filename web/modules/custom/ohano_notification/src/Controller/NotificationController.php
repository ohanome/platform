<?php

namespace Drupal\ohano_notification\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Drupal\ohano_account\Entity\Account;
use Drupal\ohano_notification\Entity\Notification;
use Drupal\ohano_profile\Entity\UserProfile;

class NotificationController extends ControllerBase {

  public function listNotifications() {
    $account = Account::forActive();

    // Creates dummy notifications
//    $activeProfile = $account->getActiveProfile();
//    $allProfiles = UserProfile::loadMultipleByUser($account->getUser());
//    $randomProfile = $allProfiles[rand(0, (count($allProfiles) - 1))];
//    while ($randomProfile->getId() == $activeProfile->getId()) {
//      $randomProfile = $allProfiles[rand(0, (count($allProfiles) - 1))];
//    }
//
//    /** @var \Drupal\ohano_notification\Service\NotificationService $notificationService */
//    $notificationService = \Drupal::service('ohano_notification.notification');
//    $notification = $notificationService->createNewFollowerNotification($account, $activeProfile, $randomProfile);
//    $notification->save();
//
//    $notificationService->deliverAllForProfile($account, $activeProfile);

    $notifications = Notification::getAllDelivered($account);
    $allNotifications = [];
    foreach ($notifications as $notification) {
      $n = Notification::load($notification);
      $allNotifications[] = [
        '#theme' => 'notification',
        '#link' => Url::fromUri($n->getLink())->setAbsolute(TRUE)->toString(),
        '#content' => $n->getContent(),
        '#type' => strtolower($n->getType()->name),
        '#timestamp' => $n->getCreatedDateTime()->format('d.m.Y H:i:s')
      ];
    }

    return [
      '#theme' => 'notification_list',
      '#notifications' => $allNotifications,
    ];
  }

}
