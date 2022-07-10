<?php

namespace Drupal\ohano_account\Feature;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\Url;
use Drupal\ohano_core\Feature\FeatureInterface;

/**
 * Provides a feature entry for the account settings.
 */
class Settings implements FeatureInterface {
  use StringTranslationTrait;

  /**
   * {@inheritdoc}
   */
  public static function getName(): TranslatableMarkup|string {
    return t('Settings');
  }

  /**
   * {@inheritdoc}
   */
  public static function getIconClass(): string {
    return 'fas fa-cog';
  }

  /**
   * {@inheritdoc}
   */
  public static function getPath(): Url {
    return Url::fromRoute('ohano_account.settings');
  }

  /**
   * {@inheritdoc}
   */
  public static function getWeight(): int {
    return 80;
  }

}
