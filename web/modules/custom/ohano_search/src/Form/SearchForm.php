<?php

namespace Drupal\ohano_search\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Ohanome\DrupalFormTrait\FormTrait;

/**
 * Provides a basic search form.
 *
 * @package Drupal\ohano_search\Form
 */
class SearchForm extends FormBase {
  use FormTrait;

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'ohano_search__search';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $form = [];

    $searchQuery = \Drupal::request()->query->get('q');
    $form['q'] = $this->buildTextField($this->t('Search query'), defaultValue: $searchQuery, required: TRUE);

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Search'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $form_state->setRedirect('ohano_search.search', ['q' => $form_state->getValue('q')]);
  }

}
