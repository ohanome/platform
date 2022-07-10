<?php

namespace Drupal\ohano_core\Form;

use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * Provides a form trait to implement forms.
 */
trait FormTrait {

  /**
   * Builds a text field.
   *
   * @param \Drupal\Core\StringTranslation\TranslatableMarkup|string $title
   *   The title of the field.
   * @param string|null $defaultValue
   *   The default value of the field.
   * @param bool $required
   *   Whether the field is required.
   *
   * @return array
   *   The text field.
   */
  public function buildTextField(
    TranslatableMarkup|string $title,
    string $defaultValue = NULL,
    bool $required = FALSE
  ): array {
    return [
      '#type' => 'textfield',
      '#title' => $title,
      '#default_value' => $defaultValue,
      '#required' => $required,
    ];
  }

  /**
   * Builds an email field.
   *
   * @param \Drupal\Core\StringTranslation\TranslatableMarkup|string $title
   *   The title of the field.
   * @param string|null $defaultValue
   *   The default value of the field.
   * @param bool $required
   *   Whether the field is required.
   *
   * @return array
   *   The email field.
   */
  public function buildEmailField(
    TranslatableMarkup|string $title,
    string $defaultValue = NULL,
    bool $required = FALSE
  ): array {
    return [
      '#type' => 'email',
      '#title' => $title,
      '#default_value' => $defaultValue,
      '#required' => $required,
    ];
  }

  /**
   * Builds a select field.
   *
   * @param \Drupal\Core\StringTranslation\TranslatableMarkup|string $title
   *   The title of the field.
   * @param array $options
   *   The options of the field.
   * @param string|null $defaultValue
   *   The default value of the field.
   * @param bool $required
   *   Whether the field is required.
   * @param bool $useChosen
   *   Whether to use Chosen for the select field.
   * @param bool $multi
   *   Whether the select field is multiple.
   *
   * @return array
   *   The select field.
   */
  public function buildSelectField(
    TranslatableMarkup|string $title,
    array $options,
    string $defaultValue = NULL,
    bool $required = FALSE,
    bool $useChosen = FALSE,
    bool $multi = FALSE
  ): array {
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

  /**
   * Builds a radio button field.
   *
   * @param \Drupal\Core\StringTranslation\TranslatableMarkup|string $title
   *   The title of the field.
   * @param array $options
   *   The options of the field.
   * @param string|null $defaultValue
   *   The default value of the field.
   * @param bool $required
   *   Whether the field is required.
   *
   * @return array
   *   The radio button field.
   */
  public function buildRadiosField(
    TranslatableMarkup|string $title,
    array $options,
    string $defaultValue = NULL,
    bool $required = FALSE
  ): array {
    return [
      '#type' => 'radios',
      '#options' => $options,
      '#title' => $title,
      '#default_value' => $defaultValue,
      '#required' => $required,
    ];
  }

  /**
   * Builds a 'default' container for reuse.
   *
   * @param \Drupal\Core\StringTranslation\TranslatableMarkup|string $title
   *   The title of the container.
   * @param bool $open
   *   Whether the container is open.
   * @param bool $tree
   *   Whether the container values are fetched as a tree.
   * @param string $type
   *   The type of the container.
   *
   * @return array
   *   The container.
   */
  public function buildDefaultContainer(
    TranslatableMarkup|string $title,
    bool $open = TRUE,
    bool $tree = TRUE,
    string $type = 'details'
  ): array {
    return [
      '#type' => $type,
      '#open' => $open,
      '#tree' => $tree,
      '#title' => $title,
    ];
  }

  /**
   * Builds a query operator field.
   *
   * @return array
   *   The query operator field.
   */
  public function buildQueryOperatorField(): array {
    $options = [
      'AND' => $this->t('all'),
      'OR' => $this->t('one of the selected'),
    ];

    return $this->buildRadiosField($this->t('Must include'), $options, defaultValue: 'OR');
  }

  /**
   * Builds a term based select field including the query operator field.
   *
   * @param string $vocabulary
   *   The vocabulary of the term based select field.
   * @param \Drupal\Core\StringTranslation\TranslatableMarkup|string $title
   *   The title of the field.
   * @param bool $multi
   *   Whether the select field is multiple.
   *
   * @return array
   *   The term based select field.
   */
  public function buildTermBasedSelect(string $vocabulary, TranslatableMarkup|string $title, bool $multi = FALSE): array {
    try {
      $terms = \Drupal::entityTypeManager()
        ->getStorage('taxonomy_term')
        ->loadTree($vocabulary);
    }
    catch (\Throwable $e) {
      \Drupal::messenger()->addError(t('Failed to load terms from vocabulary @vocabulary.', ['@vocabulary' => $vocabulary]));
      \Drupal::logger('ohano')->error($e->getMessage());
      return [];
    }
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
