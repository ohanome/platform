<?php

namespace Drupal\ohano_core\Feature;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\Url;

/**
 * Placeholder feature.
 *
 * @package Drupal\ohano_core\Feature
 */
class MessagesWip implements FeatureInterface {
  use StringTranslationTrait;

  /**
   * {@inheritdoc}
   */
  public static function getName(): TranslatableMarkup|string {
    return t('Messages (WIP)');
  }

  /**
   * {@inheritdoc}
   */
  public static function getIconClass(): string {
    return 'fas fa-comments';
  }

  /**
   * {@inheritdoc}
   */
  public static function getPath(): Url {
    return Url::fromRoute('<front>');
  }

  /**
   * {@inheritdoc}
   */
  public static function getWeight(): int {
    return 10;
  }

}
