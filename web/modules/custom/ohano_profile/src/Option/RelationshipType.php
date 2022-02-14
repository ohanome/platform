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
enum RelationshipType: string {
  case Monogamy = "Monogamy";
  case Polygamy = "Polygamy";
  case Polyamory = "Polyamory";
  case Other = "Other";

  public static function translatableFormOptions(): array {
    $options = [];
    foreach (RelationshipType::cases() as $gender) {
      // phpcs:ignore
      $options[$gender->value] = t($gender->value, [], ['context' => 'ohano OptionBase enum']);
    }
    return $options;
  }
}
