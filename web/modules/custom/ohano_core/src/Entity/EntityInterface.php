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
   *   The entity type id.
   */
  public static function entityTypeId(): string;

  /**
   * Installs the entity type.
   */
  public static function install(): void;

  /**
   * Uninstalls the entity type.
   */
  public static function uninstall(): void;

  /**
   * Deletes all remaining entities of the entity type.
   */
  public static function deleteAll(): void;

  /**
   * Loads an entity by its given field name and field value.
   *
   * @param string $field
   *   The field name.
   * @param mixed $value
   *   The field value.
   *
   * @return \Drupal\ohano_core\Entity\EntityBase|null
   *   The entity or NULL if no entity was found.
   */
  public static function loadByField(string $field, mixed $value): ?EntityBase;

  /**
   * Loads or creates an entity by its given field name and field value.
   *
   * The method will always return an entity that implements EntityInterface
   * because if no entity was found, it will be created with the given field
   * value set.
   *
   * @param string $field
   *   The field name.
   * @param mixed $value
   *   The field value.
   *
   * @return \Drupal\ohano_core\Entity\EntityBase
   *   The entity.
   */
  public static function loadOrCreateByField(string $field, mixed $value): EntityBase;

  /**
   * Converts the whole entity to an array that can be rendered.
   *
   * @return array
   *   The entity as clean array.
   */
  public function render(): array;

}
