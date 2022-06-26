<?php

namespace Drupal\ohano_core\Feature;

use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\Url;

interface FeatureInterface {

  public static function getName(): TranslatableMarkup|string;
  public static function getIconClass(): string;
  public static function getPath(): Url;
  public static function getWeight(): int;

}
