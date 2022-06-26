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
  case Follower = "Follower related";
  case Message = "Message related";
  case Post = "Post related";
  case Comment = "Comment related";
  case Security = "Security";
  case Misc = "Miscellaneous";

  public static function translatableFormOptions(): array {
    $options = [];
    foreach (NotificationType::cases() as $type) {
      // phpcs:ignore
      $options[$type->value] = t($type->value, [], ['context' => 'ohano OptionBase enum']);
    }
    return $options;
  }
}
