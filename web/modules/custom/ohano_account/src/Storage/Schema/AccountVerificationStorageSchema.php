<?php

namespace Drupal\ohano_account\Storage\Schema;

use Drupal\Core\Entity\Sql\SqlContentEntityStorageSchema;
use Drupal\Core\Field\FieldStorageDefinitionInterface;

/**
 * Storage schema for the account_verification entity that allows empty fields.
 *
 * @package Drupal\ohano_account\Storage\Schema
 */
class AccountVerificationStorageSchema extends SqlContentEntityStorageSchema {

  /**
   * {@inheritdoc}
   */
  protected function getSharedTableFieldSchema(FieldStorageDefinitionInterface $storage_definition, $table_name, array $column_mapping): array {
    $schema = parent::getSharedTableFieldSchema($storage_definition, $table_name, $column_mapping);
    $field_name = $storage_definition->getName();

    if ($table_name == $this->storage->getBaseTable()) {
      switch ($field_name) {
        case 'video':
        case 'firstname':
        case 'lastname':
        case 'birthday':
        case 'nationality':
        case 'place_of_birth':
        case 'identity_card_number':
        case 'street':
        case 'housenumber':
        case 'zip':
        case 'city':
        case 'verified':
        case 'last_comment':
          $schema['fields'][$field_name]['not null'] = FALSE;
      }
    }

    return $schema;
  }

}
