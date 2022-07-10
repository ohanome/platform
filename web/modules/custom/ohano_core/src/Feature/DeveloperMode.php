<?php

namespace Drupal\ohano_core\Feature;

use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\Url;
use Drupal\ohano_account\Entity\Account;

/**
 * Developer mode feature implementation.
 *
 * @package Drupal\ohano_core\Feature
 */
class DeveloperMode implements FeatureInterface {
  use StringTranslationTrait;

  /**
   * {@inheritdoc}
   */
  public static function getName(): TranslatableMarkup|string {
    $mode = t('Off');
    $account = Account::forActive();
    if ($account->get('developer_mode')->value) {
      $mode = t('On');
    }

    return t('Developer Mode: @mode', ['@mode' => $mode]);
  }

  /**
   * {@inheritdoc}
   */
  public static function getIconClass(): string {
    return 'fas fa-code ' . (Account::isInDeveloperMode() ? 'color-green' : 'color-red');
  }

  /**
   * {@inheritdoc}
   */
  public static function getPath(): Url {
    return Url::fromRoute('ohano_core.developer_mode');
  }

  /**
   * {@inheritdoc}
   */
  public static function getWeight(): int {
    return 99;
  }

}
