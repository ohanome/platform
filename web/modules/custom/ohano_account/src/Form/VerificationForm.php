<?php

namespace Drupal\ohano_account\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class VerificationForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'ohano_account__verification';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = [];

    $form['info'] = [
      '#type' => 'markup',
      '#markup' => $this->t('This is the last of three steps you need to do. You now need to upload a short video of yourself holding your ID card (or equivalent document) into the camera. Make sure that we can see your face, the photo and your birthday.'),
    ];

    $form['video'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('Video file'),
      '#description' => $this->t('Additionally to the above mentioned restrictions there are also technical requirements and limit: The video must be under 5MB and you must upload is as .mp4 file.'),
      '#upload_location' => 'public://verification/' . \Drupal::currentUser()->getAccountName(),
      '#upload_validators' => [
        'file_validate_extensions' => 'mp4',
        'file_validate_size' => 50 * 1000 * 1000,
      ],
      '#required' => TRUE,
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit')
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // TODO: Implement submitForm() method.
  }

}
