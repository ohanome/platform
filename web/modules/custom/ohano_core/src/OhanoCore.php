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

  /**
   * Checks if the given URL can be called.
   *
   * @param string $url
   *   The URL to check.
   *
   * @return bool
   *   TRUE if the URL can be called, FALSE otherwise.
   */
  public static function urlExists(string $url): bool {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpcode >= 200 && $httpcode < 300) {
      return TRUE;
    }

    return FALSE;
  }

}
