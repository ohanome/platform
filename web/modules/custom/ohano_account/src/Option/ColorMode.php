<?php

// phpcs:ignoreFile

namespace Drupal\ohano_account\Option;

/**
 * Enum that holds all color mode options.
 *
 * Values are backed as string values because these strings are used as translation source strings.
 *
 * @package Drupal\ohano_account\Option
 *
 * @todo: Remove 'phpcs:ignoreFile' once phpcs supports enums.
 */
enum ColorMode: string {
  case Light = "Light";
  case Dark = "Dark";

  public static function translatableFormOptions(): array {
    $options = [];
    foreach (ColorMode::cases() as $gender) {
      // phpcs:ignore
      $options[$gender->value] = t($gender->value, [], ['context' => 'ohano OptionBase enum']);
    }
    return $options;
  }
}
