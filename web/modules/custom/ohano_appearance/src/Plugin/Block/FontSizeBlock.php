<?php

namespace Drupal\ohano_appearance\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a block to switch the brightness.
 *
 * @Block(
 *   id = "ohano_appearance__font_size_block",
 *   admin_label = @Translation("Font Size Block"),
 *   category = @Translation("ohano Appearance"),
 * )
 */
class FontSizeBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#theme' => 'block__font_size',
    ];
  }

}
