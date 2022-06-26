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
enum RelationshipStatus: string {
  case Single = "Single";
  case Relationship = "In a relationship";
  case Married = "Married";
  case Complicated = "It's complicated";
  case Divorced = "Divorced";
  case Widowed = "Widowed";
  case OpenRelationship = "In an open relationship";
  case Other = "Other";

  public static function translatableFormOptions(): array {
    $options = [];
    foreach (RelationshipStatus::cases() as $gender) {
      // phpcs:ignore
      $options[$gender->value] = t($gender->value, [], ['context' => 'ohano OptionBase enum']);
    }
    return $options;
  }
}
