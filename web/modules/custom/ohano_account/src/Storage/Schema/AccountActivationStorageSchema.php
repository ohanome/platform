<?php

namespace Drupal\ohano_account\Storage\Schema;

use Drupal\Core\Entity\Sql\SqlContentEntityStorageSchema;
use Drupal\Core\Field\FieldStorageDefinitionInterface;

/**
 * Storage schema for the AccountActivation entity.
 *
 * @package Drupal\ohano_account\Storage\Schema
 */
class AccountActivationStorageSchema extends SqlContentEntityStorageSchema {

  /**
   * {@inheritdoc}
   */
  protected function getSharedTableFieldSchema(FieldStorageDefinitionInterface $storage_definition, $table_name, array $column_mapping) {
    $schema = parent::getSharedTableFieldSchema($storage_definition, $table_name, $column_mapping);
    $field_name = $storage_definition->getName();

    if ($table_name == $this->storage->getBaseTable()) {
      switch ($field_name) {
        case 'activated_on':
          $schema['fields'][$field_name]['not null'] = FALSE;
      }
    }

    return $schema;
  }

}
