<?php

namespace Drupal\ohano_appearance\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a block to switch the color shade.
 *
 * @Block(
 *   id = "ohano_appearance__color_shade_block",
 *   admin_label = @Translation("Color Shade Block"),
 *   category = @Translation("ohano Appearance"),
 * )
 */
class ColorShadeBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#theme' => 'block__color_shade',
    ];
  }

}
