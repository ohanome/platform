<?php

namespace Drupal\ohano_core\Form;

use Drupal\Core\StringTranslation\TranslatableMarkup;

trait FormTrait {
  public function buildDefaultContainer(TranslatableMarkup|string $title, bool $open = TRUE) {
    return [
      '#type' => 'details',
      '#open' => $open,
      '#tree' => TRUE,
      '#title' => $title,
    ];
  }
}
