<?php

namespace Drupal\ohano_search\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\ohano_core\Form\FormTrait;

class SearchForm extends FormBase {
  use FormTrait;

  public function getFormId() {
    return 'ohano_search__search';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = [];

    $searchQuery = \Drupal::request()->query->get('q');
    $form['q'] = $this->buildTextField($this->t('Search query'), defaultValue: $searchQuery, required: TRUE);

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Search'),
    ];

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $form_state->setRedirect('ohano_search.search', ['q' => $form_state->getValue('q')]);
  }

}
