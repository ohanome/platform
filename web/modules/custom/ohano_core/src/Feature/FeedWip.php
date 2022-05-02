<?php

namespace Drupal\ohano_core\Feature;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\Url;

class FeedWip implements FeatureInterface {
  use StringTranslationTrait;

  public static function getName(): TranslatableMarkup|string {
    return t('Feed (WIP)');
  }

  public static function getIconClass(): string {
    return 'fas fa-home';
  }

  public static function getPath(): Url {
    return Url::fromRoute('<front>');
  }

  public static function getWeight(): int {
    return 0;
  }

}
