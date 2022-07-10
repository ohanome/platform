<?php

namespace Drupal\ohano_core\Feature;

use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\Url;

/**
 * Provides a feature interface to implement features.
 */
interface FeatureInterface {

  /**
   * Gets the name of the feature.
   *
   * @return \Drupal\Core\StringTranslation\TranslatableMarkup|string
   *   The name of the feature.
   */
  public static function getName(): TranslatableMarkup|string;

  /**
   * Gets the icon class of the feature.
   *
   * @return string
   *   The icon class of the feature.
   */
  public static function getIconClass(): string;

  /**
   * Gets the path of the feature.
   *
   * @return \Drupal\Core\Url
   *   The path of the feature.
   */
  public static function getPath(): Url;

  /**
   * Gets the weight of the feature.
   *
   * @return int
   *   The weight of the feature.
   */
  public static function getWeight(): int;

}
