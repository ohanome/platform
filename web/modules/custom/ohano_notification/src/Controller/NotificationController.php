<?php

namespace Drupal\ohano_notification\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\ohano_account\Entity\Account;
use Drupal\ohano_notification\Entity\Notification;

class NotificationController extends ControllerBase {

  public function listNotifications() {
    $account = Account::forActive();
    $notifications = Notification::getAllDelivered($account);
    dd($notifications);
  }

}
