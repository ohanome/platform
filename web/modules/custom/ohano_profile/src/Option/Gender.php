<?php

// phpcs:ignoreFile

namespace Drupal\ohano_profile\Option;

/**
 * Enum that holds all gender options.
 *
 * Values are backed as string values because these strings are used as translation source strings.
 *
 * @package Drupal\ohano_profile\Option
 *
 * @todo: Remove 'phpcs:ignoreFile' once phpcs supports enums.
 */
enum Gender: string {
  case Female = "female";
  case Male = "male";
  case Nonbinary = "non-binary";
  case Transgender = "transgender";
  case Secret = "secret";
  case Other = "other";

  public static function translatableFormOptions(): array {
    $options = [];
    foreach (Gender::cases() as $gender) {
      // phpcs:ignore
      $options[$gender->value] = t($gender->value, [], ['context' => 'ohano OptionBase enum']);
    }
    return $options;
  }
}
