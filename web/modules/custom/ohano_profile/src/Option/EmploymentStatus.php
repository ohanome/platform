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
enum EmploymentStatus: string {
  case FulltimeEmployed = "Fulltime employed";
  case ParttimeEmployed = "Part time employed";
  case Unemployed = "Unemployed";
  case School = "School";
  case Apprenticeship = "Apprenticeship";
  case Traineeship = "Traineeship";
  case Internship = "Internship";
  case Student = "Student";
  case Other = "Other";

  public static function translatableFormOptions(): array {
    $options = [];
    foreach (EmploymentStatus::cases() as $gender) {
      // phpcs:ignore
      $options[$gender->value] = t($gender->value, [], ['context' => 'ohano OptionBase enum']);
    }
    return $options;
  }
}
