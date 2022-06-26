<?php

namespace Drupal\ohano_stats\Feature;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Url;
use Drupal\ohano_core\Feature\FeatureInterface;

class Statistics implements FeatureInterface {
  use StringTranslationTrait;

  public static function getName(): string {
    return t('Statistics');
  }

  public static function getIconClass(): string {
    return 'fas fa-chart-pie';
  }

  public static function getPath(): Url {
    return Url::fromRoute('ohano_stats.index');
  }

  public static function getWeight(): int {
    return 90;
  }

}
