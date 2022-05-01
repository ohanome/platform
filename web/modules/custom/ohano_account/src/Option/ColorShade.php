<?php

// phpcs:ignoreFile

namespace Drupal\ohano_account\Option;

/**
 * Enum that holds all color shade options.
 *
 * Values are backed as string values because these strings are used as translation source strings.
 *
 * @package Drupal\ohano_account\Option
 *
 * @todo: Remove 'phpcs:ignoreFile' once phpcs supports enums.
 */
enum ColorShade: string {
  case Slate = "Slate";
  case Gray = "Gray";
  case Zinc = "Zinc";
  case Neutral = "Neutral";
  case Stone = "Stone";

  public static function translatableFormOptions(): array {
    $options = [];
    foreach (ColorShade::cases() as $gender) {
      // phpcs:ignore
      $options[$gender->value] = t($gender->value, [], ['context' => 'ohano OptionBase enum']);
    }
    return $options;
  }
}
