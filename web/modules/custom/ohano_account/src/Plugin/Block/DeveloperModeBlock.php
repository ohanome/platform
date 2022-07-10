<?php

namespace Drupal\ohano_account\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\ohano_account\Entity\Account;

/**
 * Provides the feature block with links to the main platform features.
 *
 * @Block(
 *   id = "ohano_account__devmode_block",
 *   admin_label = @Translation("Developer mode Block"),
 *   category = @Translation("ohano Account"),
 * )
 */
class DeveloperModeBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    if (!Account::isInDeveloperMode()) {
      return [];
    }

    $developerModeInfo = [];
    \Drupal::moduleHandler()->alter('developer_mode_info', $developerModeInfo);

    dump($developerModeInfo);
    return [
      '#cache' => [
        'max-age' => 0,
      ],
    ];
  }

}
