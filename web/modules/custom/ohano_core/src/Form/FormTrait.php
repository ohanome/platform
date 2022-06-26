<?php

namespace Drupal\ohano_core\Form;

use Drupal\Core\StringTranslation\TranslatableMarkup;

trait FormTrait {
  public function buildTextField(TranslatableMarkup|string $title, string $defaultValue = NULL, bool $required = FALSE) {
    return [
      '#type' => 'textfield',
      '#title' => $title,
      '#default_value' => $defaultValue,
      '#required' => $required,
    ];
  }

  public function buildEmailField(TranslatableMarkup|string $title, string $defaultValue = NULL, bool $required = FALSE) {
    return [
      '#type' => 'email',
      '#title' => $title,
      '#default_value' => $defaultValue,
      '#required' => $required,
    ];
  }

  public function buildSelectField(TranslatableMarkup|string $title, array $options, string $defaultValue = NULL, bool $required = FALSE, bool $useChosen = FALSE, bool $multi = FALSE) {
    return [
      '#type' => 'select',
      '#chosen' => $useChosen,
      '#multiple' => $multi,
      '#options' => $options,
      '#title' => $title,
      '#default_value' => $defaultValue,
      '#required' => $required,
    ];
  }

  public function buildRadiosField(TranslatableMarkup|string $title, array $options, string $defaultValue = NULL, bool $required = FALSE) {
    return [
      '#type' => 'radios',
      '#options' => $options,
      '#title' => $title,
      '#default_value' => $defaultValue,
      '#required' => $required,
    ];
  }

  public function buildDefaultContainer(TranslatableMarkup|string $title, bool $open = TRUE) {
    return [
      '#type' => 'details',
      '#open' => $open,
      '#tree' => TRUE,
      '#title' => $title,
    ];
  }

  public function buildQueryOperatorField() {
    $options = [
        'AND' => $this->t('all'),
        'OR' => $this->t('one of the selected'),
    ];

    return $this->buildRadiosField($this->t('Must include'), $options, defaultValue: 'OR');
  }

  public function buildTermBasedSelect(string $vocabulary, TranslatableMarkup|string $title, bool $multi = FALSE) {
    $terms = \Drupal::entityTypeManager()
      ->getStorage('taxonomy_term')
      ->loadTree($vocabulary);
    $options = [];
    foreach ($terms as $term) {
      $options[$term->tid] = $term->name;
    }

    $build = $this->buildDefaultContainer($title);
    $build['options'] = $this->buildSelectField($title, $options, useChosen: TRUE, multi: $multi);
    $build['operator'] = $this->buildQueryOperatorField();

    return $build;
  }
}
