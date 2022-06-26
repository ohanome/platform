<?php

namespace Drupal\ohano_appearance\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a block to switch the brightness.
 *
 * @Block(
 *   id = "ohano_appearance__brightness_block",
 *   admin_label = @Translation("Brightness Block"),
 *   category = @Translation("ohano Appearance"),
 * )
 */
class BrightnessModeBlock extends BlockBase {

  public function build() {
    return [
      '#theme' => 'block__color_mode',
    ];
  }

}
