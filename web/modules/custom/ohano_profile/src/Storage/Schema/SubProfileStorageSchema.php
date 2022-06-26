<?php

namespace Drupal\ohano_profile\Storage\Schema;

use Drupal\Core\Entity\Sql\SqlContentEntityStorageSchema;
use Drupal\Core\Field\FieldStorageDefinitionInterface;

/**
 * Class UserProfileStorageSchema.
 *
 * Allows setting fields to be nullable.
 *
 * @package Drupal\ohano_profile\Storage\Schema
 */
class SubProfileStorageSchema extends SqlContentEntityStorageSchema {

  /**
   * {@inheritdoc}
   */
  protected function getSharedTableFieldSchema(FieldStorageDefinitionInterface $storage_definition, $table_name, array $column_mapping): array {
    $schema = parent::getSharedTableFieldSchema($storage_definition, $table_name, $column_mapping);
    $field_name = $storage_definition->getName();

    if ($table_name == $this->storage->getBaseTable()) {
      if (!in_array($field_name, [
        'id',
        'uuid',
        'created',
        'updated',
        'profile',
      ])) {
        $schema['fields'][$field_name]['not null'] = FALSE;
      }
    }

    return $schema;
  }

}
