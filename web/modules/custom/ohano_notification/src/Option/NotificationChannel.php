<?php

// phpcs:ignoreFile

namespace Drupal\ohano_notification\Option;

/**
 * Enum that holds all color mode options.
 *
 * Values are backed as string values because these strings are used as translation source strings.
 *
 * @package Drupal\ohano_notification\Option
 *
 * @todo: Remove 'phpcs:ignoreFile' once phpcs supports enums.
 */
enum NotificationChannel: string {
  case None = "None";
  case Email = "Email";
  case InApp = "In-App";
  case Push = "Push";
  case CarrierPigeon = "Carrier pigeon";

  public static function translatableFormOptions(): array {
    $options = [];
    foreach (NotificationChannel::cases() as $channel) {
      // phpcs:ignore
      $options[$channel->value] = t($channel->value, [], ['context' => 'ohano OptionBase enum']);
    }
    return $options;
  }
}
