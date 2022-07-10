<?php

namespace Drupal\ohano_profile\Entity;

/**
 * Interface for use with sub-profiles.
 *
 * @package Drupal\ohano_profile\Entity
 */
interface SubProfileInterface {

  /**
   * Renders the sub profile form.
   *
   * @param \Drupal\ohano_profile\Entity\SubProfileBase $subProfile
   *   The sub profile to render the form for.
   *
   * @return array
   *   The form array.
   */
  public static function renderForm(SubProfileBase $subProfile): array;

}
