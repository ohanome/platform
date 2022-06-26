<?php

namespace Drupal\ohano_account\Form\Admin;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Entity\EntityStorageException;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\ohano_account\Entity\AccountVerification;
use Drupal\ohano_mail\OhanoMail;
use Drupal\ohano_mail\OhanoMailer;
use PHPMailer\PHPMailer\Exception;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Administration form for verification requests.
 *
 * @package Drupal\ohano_account\Form\Admin
 */
class VerificationForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'ohano_account_admin_verification';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, int $id = NULL): array {
    if (empty($id)) {
      $this->messenger()->addError($this->t('Verification id is empty.'));
      (new RedirectResponse(Url::fromRoute('ohano_account.admin.account.verification')->toString()))->send();
    }

    /** @var \Drupal\ohano_account\Entity\AccountVerification $verification */
    $verification = AccountVerification::load($id);

    $form = [];

    $form['verification_id'] = [
      '#type' => 'hidden',
      '#value' => $id,
    ];

    $form['username'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Username'),
      '#value' => $verification->getUser()->getAccountName(),
      '#attributes' => [
        'disabled' => 'disabled',
      ],
    ];

    $form['user_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('User ID'),
      '#value' => $verification->getUser()->id(),
      '#attributes' => [
        'disabled' => 'disabled',
      ],
    ];

    $video = $verification->getVideo();
    if ($video) {
      /** @var \Drupal\Core\File\FileUrlGenerator $url */
      $generator = \Drupal::service('file_url_generator');
      $url = $generator->generateAbsoluteString($video->getFileUri());
      $form['video'] = [
        '#type' => 'markup',
        '#title' => $this->t("Video"),
        '#markup' => $this->t('Video file: <a href="@url" target="_blank">@url</a><br />', ['url' => $url]),
      ];
    }
    else {
      $form['video'] = [
        '#type' => 'markup',
        '#title' => $this->t("Video"),
        '#markup' => '<i>' . $this->t("No file submitted yet.") . '</i><br />',
      ];
    }

    $form['firstname'] = [
      '#type' => 'textfield',
      '#title' => $this->t('First name'),
      '#default_value' => $verification->getFirstname(),
    ];

    $form['lastname'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Last name'),
      '#default_value' => $verification->getLastname(),
    ];

    $form['birthday'] = [
      '#type' => 'date',
      '#title' => $this->t('Birthday'),
      '#default_value' => $verification->getBirthday()?->format('Y-m-d'),
    ];

    $form['nationality'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Nationality'),
      '#default_value' => $verification->getNationality(),
    ];

    $form['place_of_birth'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Place of birth'),
      '#default_value' => $verification->getPlaceOfBirth(),
    ];

    $form['identity_card_number'] = [
      '#type' => 'textfield',
      '#title' => $this->t('ID card number'),
      '#default_value' => $verification->getIdentityCardNumber(),
    ];

    $form['street'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Street'),
      '#default_value' => $verification->getStreet(),
    ];

    $form['housenumber'] = [
      '#type' => 'textfield',
      '#title' => $this->t('House number'),
      '#default_value' => $verification->getHousenumber(),
    ];

    $form['zip'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Postal code'),
      '#default_value' => $verification->getZip(),
    ];

    $form['city'] = [
      '#type' => 'textfield',
      '#title' => $this->t('City'),
      '#default_value' => $verification->getCity(),
    ];

    $form['comment'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Comment'),
      '#description' => $this->t('If you add a new comment, please delete the old one as this is probably not valid or relevant anymore.'),
      '#default_value' => $verification->getLastComment(),
    ];

    $form['is_verified'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Is valid'),
      '#default_value' => (int) $verification->isVerified(),
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $verificationId = $form_state->getValue('verification_id');
    $verification = AccountVerification::load($verificationId);

    $values = $form_state->getValues();
    if (!empty($values['firstname'])) {
      $verification->setFirstname($values['firstname']);
    }
    if (!empty($values['lastname'])) {
      $verification->setLastname($values['lastname']);
    }
    if (!empty($values['birthday'])) {
      $verification->setBirthday(DrupalDateTime::createFromFormat('Y-m-d', $values['birthday']));
    }
    if (!empty($values['nationality'])) {
      $verification->setNationality($values['nationality']);
    }
    if (!empty($values['place_of_birth'])) {
      $verification->setPlaceOfBirth($values['place_of_birth']);
    }
    if (!empty($values['identity_card_number'])) {
      $verification->setIdentityCardNumber($values['identity_card_number']);
    }
    if (!empty($values['street'])) {
      $verification->setStreet($values['street']);
    }
    if (!empty($values['housenumber'])) {
      $verification->setHousenumber($values['housenumber']);
    }
    if (!empty($values['zip'])) {
      $verification->setZip($values['zip']);
    }
    if (!empty($values['city'])) {
      $verification->setCity($values['city']);
    }
    if (!empty($values['comment'])) {
      $verification->setLastComment($values['comment']);
    }

    $verification->setIsVerified((bool) $values['is_verified']);

    try {
      $verification->save();
    }
    catch (EntityStorageException $e) {
      $this->messenger()->addError($this->t('The verification request could not be saved.'));
      $this->logger('ohano_account')->critical($e->getMessage());
    }

    $mail = $verification->isVerified() ? OhanoMail::AccountVerificationAccepted : OhanoMail::AccountVerificationUpdated;
    $mailer = new OhanoMailer($mail);
    $mailer->renderBody([
      'username' => $verification->getUser()->getAccountName(),
    ]);

    if ($values['is_verified']) {
      $mailer->Subject = (string) $this->t('Account verification has been accepted');
    }
    else {
      $mailer->Subject = (string) $this->t('Account verification has been updated');
    }

    try {
      $mailer->addAddress($verification->getUser()->getEmail(), $verification->getUser()->getAccountName());
    }
    catch (Exception $e) {
      \Drupal::messenger()->addError($this->t('Something went wrong when creating your account. Please try again.'));
      \Drupal::logger('ohano_account')->critical($e->getMessage());
      return;
    }

    try {
      $mailer->send();
    }
    catch (Exception $e) {
      \Drupal::messenger()->addError($this->t('Something went wrong when creating your account. Please try again.'));
      \Drupal::logger('ohano_account')->critical($e->getMessage());
      return;
    }

    $this->messenger()->addMessage($this->t('Verification request has been saved successfully.'));
    $form_state->setRedirect('ohano_account.admin.account.verification');
  }

}
