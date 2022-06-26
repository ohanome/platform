<?php

namespace Drupal\ohano_core\Feature;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\Url;

class ActivityWip implements FeatureInterface {
  use StringTranslationTrait;

  public static function getName(): TranslatableMarkup|string {
    return t('Activity (WIP)');
  }

  public static function getIconClass(): string {
    return 'fas fa-at';
  }

  public static function getPath(): Url {
    return Url::fromRoute('<front>');
  }

  public static function getWeight(): int {
    return 20;
  }

}
