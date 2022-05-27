<?php

namespace Drupal\ohano_account\Feature;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\Url;
use Drupal\ohano_core\Feature\FeatureInterface;

class Settings implements FeatureInterface {
  use StringTranslationTrait;

  public static function getName(): TranslatableMarkup|string {
    return t('Settings');
  }

  public static function getIconClass(): string {
    return 'fas fa-cog';
  }

  public static function getPath(): Url {
    return Url::fromRoute('ohano_account.settings');
  }

  public static function getWeight(): int {
    return 80;
  }

}
