<?php

namespace Drupal\ohano_account\Form;

use Drupal;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;
use Drupal\ohano_account\Entity\AccountVerification;

/**
 * Class providing the verification form.
 *
 * @package Drupal\ohano_account
 */
class VerificationForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'ohano_account__verification';
  }

  private function getFileField(): array {
    return [
      '#type' => 'managed_file',
      '#title' => $this->t('Video file'),
      '#description' => $this->t('Additionally to the above mentioned restrictions there are also technical requirements and limit: The video must be under 5MB and you must upload is as .mp4 file.'),
      '#upload_location' => 'public://verification/' . md5(\Drupal::currentUser()
          ->getAccountName()),
      '#upload_validators' => [
        'file_validate_extensions' => [
          'mp4',
        ],
        'file_validate_size' => [
          50000000,
        ],
      ],
      '#required' => TRUE,
      '#multiple' => FALSE,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = [];

    $verificationQuery = \Drupal::entityQuery('account_verification')
      ->condition('user', \Drupal::currentUser()->id())
      ->execute();

    if (empty($verificationQuery) || count($verificationQuery) > 1) {
      $this->messenger()->addError($this->t("Sorry, something went wrong when we created your account."));
    }

    $verification = AccountVerification::load(array_values($verificationQuery)[0]);

    $form['verification_id'] = [
      '#type' => 'hidden',
      '#value' => $verification->id(),
    ];

    $form['info'] = [
      '#type' => 'markup',
      '#markup' => $this->t('This is the last of three steps you need to do. You now need to upload a short video of yourself holding your ID card (or equivalent document) into the camera. Make sure that we can see your face, the photo and your birthday.'),
    ];

    $allowSubmit = TRUE;
    if ($verification->getVideo() == NULL && empty($verification->getLastComment())) {
      $form['video'] = $this->getFileField();
    }
    elseif ($verification->getVideo() !== NULL && !empty($verification->getLastComment())) {
      $this->messenger()->addStatus($this->t("Your verification request has been reviewed but it did not pass. Please have a look at the moderation note below."));
      $form['last_comment'] = [
        '#type' => 'textarea',
        '#title' => $this->t('Note'),
        '#value' => $verification->getLastComment(),
        '#attributes' => [
          'disabled' => 'disabled',
        ],
      ];

      $form['video'] = $this->getFileField();
    }
    else {
      $this->messenger()->addStatus($this->t("Your verification request is currently being reviewed. Please be patient."));
      $allowSubmit = FALSE;
    }

    if ($allowSubmit) {
      $form['actions']['submit'] = [
        '#type' => 'submit',
        '#value' => $this->t('Submit'),
      ];
    }

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $files = $form_state->getValue('video', 0);
    foreach ($files as $fileId) {
      if (!empty($fileId)) {
        $file = File::load($fileId);
        $file->setFilename(md5(time()) . md5($file->getFilename()) . '.' . $file->getFilename());
        $file->setPermanent();
        try {
          $file->save();

          $verification = AccountVerification::load($form_state->getValue('verification_id'));
          $verification->setVideo($file);
          $verification->setLastComment('');
          $verification->save();

          $this->messenger()->addMessage($this->t("Your verification will be reviewed shortly."));
        } catch (Drupal\Core\Entity\EntityStorageException $e) {
          Drupal::messenger()->addError('Verification failed due to technical errors.');
          Drupal::logger('ohano_account')->critical($e->getMessage());
          return;
        }
      }
    }
  }

}
