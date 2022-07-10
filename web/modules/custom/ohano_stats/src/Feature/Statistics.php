<?php

namespace Drupal\ohano_stats\Feature;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Url;
use Drupal\ohano_core\Feature\FeatureInterface;

/**
 * Provides a feature to display statistics.
 */
class Statistics implements FeatureInterface {
  use StringTranslationTrait;

  /**
   * {@inheritdoc}
   */
  public static function getName(): string {
    return t('Statistics');
  }

  /**
   * {@inheritdoc}
   */
  public static function getIconClass(): string {
    return 'fas fa-chart-pie';
  }

  /**
   * {@inheritdoc}
   */
  public static function getPath(): Url {
    return Url::fromRoute('ohano_stats.index', ['stat_type' => 'all']);
  }

  /**
   * {@inheritdoc}
   */
  public static function getWeight(): int {
    return 90;
  }

}
