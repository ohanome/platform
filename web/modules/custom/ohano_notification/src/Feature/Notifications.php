<?php

namespace Drupal\ohano_notification\Feature;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\Url;
use Drupal\ohano_account\Entity\Account;
use Drupal\ohano_core\Feature\FeatureInterface;

class Notifications implements FeatureInterface {
  use StringTranslationTrait;

  public static function getName(): TranslatableMarkup|string {
    return t('Notifications');
  }

  public static function getIconClass(): string {
    $account = Account::forActive();
    /** @var \Drupal\ohano_notification\Service\NotificationService $notificationService */
    $notificationService = \Drupal::service('ohano_notification.notification');
    $hasUnreadNotifications = $notificationService
      ->accountHasUnreadNotifications($account);
    return 'fas fa-bell' . ($hasUnreadNotifications ? ' color-secondary' : '');
  }

  public static function getPath(): Url {
    return Url::fromRoute('ohano_notification.list');
  }

  public static function getWeight(): int {
    return 30;
  }

}
