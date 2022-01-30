<?php

namespace Drupal\ohano_core;

use Drupal\Core\Field\BaseFieldDefinition;

/**
 * Class containing static methods used at different places.
 *
 * @package Drupal\ohano_core
 */
class OhanoCore {

  /**
   * Adds default fields to the given field array.
   *
   * @param array $fields
   *   The fields array to add the default fields to.
   */
  public static function createEntityDefaultFields(array &$fields = []): void {
    // Standard field, used as unique if primary index.
    $fields['id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('ID'))
      ->setDescription(t('The ID of the Advertiser entity.'))
      ->setReadOnly(TRUE);

    // Standard field, unique outside of the scope of the current project.
    $fields['uuid'] = BaseFieldDefinition::create('uuid')
      ->setLabel(t('UUID'))
      ->setDescription(t('The UUID of the Advertiser entity.'))
      ->setReadOnly(TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time the entity was created.'));

    $fields['updated'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Updated'))
      ->setDescription(t('The time the entity has been updated.'));
  }

}
