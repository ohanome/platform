<?php

namespace Drupal\ohano_core\Entity;

/**
 * Interface providing additional methods needed by inheriting classes.
 *
 * @package Drupal\ohano_core\Entity
 */
interface EntityInterface {

  /**
   * Converts the whole entity to an array that can be rendered.
   *
   * @return array
   *   The entity as clean array.
   */
  public function render(): array;

}
