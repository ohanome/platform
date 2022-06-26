<?php

// phpcs:ignoreFile

namespace Drupal\ohano_account\Option;

/**
 * Enum that holds all font size options.
 *
 * Values are backed as string values because these strings are used as translation source strings.
 *
 * @package Drupal\ohano_account\Option
 *
 * @todo: Remove 'phpcs:ignoreFile' once phpcs supports enums.
 */
enum SubscriptionTier: string {
  case None = "None";

  case Basic = "Basic";
  case Advanced = "Advanced";
  case Pro = "Pro";

  case Gold = "Gold";
  case Diamond = "Diamond";

  case BasicPlus = "Basic+";
  case AdvancedPlus = "Advanced+";
  case ProPlus = "Pro+";
  case GoldPlus = "Gold+";
  case DiamondPlus = "Diamond+";

  public static function translatableFormOptions(): array {
    $options = [];
    foreach (SubscriptionTier::cases() as $tier) {
      // phpcs:ignore
      $options[$tier->name] = t($tier->value, [], ['context' => 'ohano OptionBase enum']);
    }
    return $options;
  }
}
