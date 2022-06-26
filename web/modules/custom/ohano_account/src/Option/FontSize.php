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
enum FontSize: string {
  case XXS = "XXS";
  case XS = "XS";
  case S = "S";
  case M = "M";
  case L = "L";
  case XL = "XL";
  case XXL = "XXL";

  public static function translatableFormOptions(): array {
    $options = [];
    foreach (FontSize::cases() as $gender) {
      // phpcs:ignore
      $options[$gender->value] = t($gender->value, [], ['context' => 'ohano OptionBase enum']);
    }
    return $options;
  }
}
