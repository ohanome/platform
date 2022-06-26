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
enum ProfileType: string {
  case Personal = "personal";
  case Company = "company";
  case Artist = "artist";
  case Musician = "musician";
  case Influencer = "influencer";
  case Streamer = "streamer";

  public static function translatableFormOptions(): array {
    $options = [];
    foreach (ProfileType::cases() as $type) {
      // phpcs:ignore
      $options[$type->value] = t($type->value, [], ['context' => 'ohano OptionBase enum']);
    }
    return $options;
  }
}
