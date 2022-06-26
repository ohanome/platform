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
enum EducationDegree: string {
  case NoDegree = "No degree";
  case SecondarySchool = "Secondary school";
  case Baccalaureate = "Baccalaureate";
  case Bachelor = "Bachelor";
  case Master = "Master";
  case Apprenticeship = "Apprenticeship";
  case Other = "Other";

  public static function translatableFormOptions(): array {
    $options = [];
    foreach (EducationDegree::cases() as $gender) {
      // phpcs:ignore
      $options[$gender->value] = t($gender->value, [], ['context' => 'ohano OptionBase enum']);
    }
    return $options;
  }
}
