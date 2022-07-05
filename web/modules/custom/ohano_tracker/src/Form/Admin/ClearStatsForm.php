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

class ClearStatsForm extends FormBase {

  public function getFormId() {
    return 'ohano_tracker_clear_stats_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Clear stats'),
    ];

    return $form;
  }

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
