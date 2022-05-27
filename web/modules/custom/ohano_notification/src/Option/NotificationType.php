<?php

// phpcs:ignoreFile

namespace Drupal\ohano_notification\Option;

/**
 * Enum that holds all color mode options.
 *
 * Values are backed as string values because these strings are used as translation source strings.
 *
 * @package Drupal\ohano_account\Option
 *
 * @todo: Remove 'phpcs:ignoreFile' once phpcs supports enums.
 */
enum NotificationType: string {
  case Newsletter = "Newsletter";
  case NewFollower = "New follower";
  case NewMessage = "New message";
  case Security = "Security";

  public static function translatableFormOptions(): array {
    $options = [];
    foreach (NotificationType::cases() as $type) {
      // phpcs:ignore
      $options[$type->value] = t($type->value, [], ['context' => 'ohano OptionBase enum']);
    }
    return $options;
  }
}
