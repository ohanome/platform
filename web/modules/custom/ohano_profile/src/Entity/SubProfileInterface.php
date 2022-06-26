<?php

namespace Drupal\ohano_profile\Entity;

interface SubProfileInterface {

  public static function renderForm(SubProfileBase $subProfile): array;

}
