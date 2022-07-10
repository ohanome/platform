<?php

namespace Drupal\ohano_tracker\Form\Admin;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\ohano_tracker\Entity\Day;
use Drupal\ohano_tracker\Entity\Month;
use Drupal\ohano_tracker\Entity\PathRequest;
use Drupal\ohano_tracker\Entity\Platform;
use Drupal\ohano_tracker\Entity\RequestTime;
use Drupal\ohano_tracker\Entity\UserAgent;
use Drupal\ohano_tracker\Entity\Weekday;
use Drupal\ohano_tracker\Entity\Year;

/**
 * Provides a form to clear the stats.
 *
 * @package Drupal\ohano_tracker\Form\Admin
 */
class ClearStatsForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'ohano_tracker_clear_stats_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Clear stats'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    Day::deleteAll();
    Month::deleteAll();
    PathRequest::deleteAll();
    Platform::deleteAll();
    RequestTime::deleteAll();
    UserAgent::deleteAll();
    Weekday::deleteAll();
    Year::deleteAll();
    \Drupal::messenger()->addMessage($this->t('Stats cleared.'));
  }

}
