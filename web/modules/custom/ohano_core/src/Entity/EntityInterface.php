<?php

namespace Drupal\ohano_core\Entity;

/**
 * Interface providing additional methods needed by inheriting classes.
 *
 * @package Drupal\ohano_core\Entity
 */
interface EntityInterface {

  /**
   * Returns the entity type id as static variable.
   *
   * @return string
   */
  public static function entityTypeId(): string;

  /**
   * Installs the entity type.
   *
   * @return void
   */
  public static function install(): void;

  /**
   * Uninstalls the entity type.
   *
   * @return void
   */
  public static function uninstall(): void;

  /**
   * Deletes all remaining entities of the entity type.
   *
   * @return void
   */
  public static function deleteAll(): void;

  /**
   * Converts the whole entity to an array that can be rendered.
   *
   * @return array
   *   The entity as clean array.
   */
  public function render(): array;

}
