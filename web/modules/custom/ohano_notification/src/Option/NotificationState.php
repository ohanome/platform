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
enum NotificationState: string {
  case Created = "created";
  case Delivered = "delivered";
  case Read = "read";

  public static function translatableFormOptions(): array {
    $options = [];
    foreach (NotificationState::cases() as $state) {
      // phpcs:ignore
      $options[$state->value] = t($state->value, [], ['context' => 'ohano OptionBase enum']);
    }
    return $options;
  }
}
