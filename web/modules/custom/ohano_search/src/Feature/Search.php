<?php

namespace Drupal\ohano_search\Feature;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\Url;
use Drupal\ohano_core\Feature\FeatureInterface;

class Search implements FeatureInterface {
  use StringTranslationTrait;

  public static function getName(): TranslatableMarkup|string {
    return t('Search');
  }

  public static function getIconClass(): string {
    return 'fas fa-search';
  }

  public static function getPath(): Url {
    return Url::fromRoute('ohano_search.search');
  }

  public static function getWeight(): int {
    return 40;
  }

}
