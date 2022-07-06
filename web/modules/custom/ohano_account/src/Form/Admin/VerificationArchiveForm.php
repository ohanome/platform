<?php

namespace Drupal\ohano_account\Form\Admin;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\ohano_account\Entity\AccountVerification;
use Symfony\Component\HttpFoundation\RedirectResponse;

class VerificationArchiveForm extends FormBase {

  public function getFormId() {
    return 'ohano_account_admin_verification_archive';
  }

  public function buildForm(array $form, FormStateInterface $form_state, int $id = NULL) {
    if ($id === NULL) {
      $this->messenger()->addError($this->t('Verification id is empty.'));
      (new RedirectResponse(Url::fromRoute('ohano_account.admin.account.verification')->toString()))->send();
    }

    $verification = AccountVerification::load($id);
    if (!$verification->isVerified()) {
      $this->messenger()->addError($this->t('Verification is not verified.'));
      (new RedirectResponse(Url::fromRoute('ohano_account.admin.account.verification')->toString()))->send();
    }

    \Drupal::messenger()->addWarning($this->t('By archiving the verification, no one will be able to view this verification again. Continue?'));
    return [
      'verification_id' => [
        '#type' => 'hidden',
        '#value' => $id,
      ],
      'submit' => [
        '#type' => 'submit',
        '#value' => $this->t('Archive'),
      ]
    ];
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $verificationId = $form_state->getValue('verification_id');
    /** @var \Drupal\ohano_account\Entity\AccountVerification $verification */
    $verification = AccountVerification::load($verificationId);
    if (!$verification->isVerified()) {
      $this->messenger()->addError($this->t('Verification is not verified.'));
      (new RedirectResponse(Url::fromRoute('ohano_account.admin.account.verification')->toString()))->send();
    }

    $verificationArray = $verification->render();
    $verificationArray['_archived_on'] = (new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s') . ' UTC';
    $verificationArray['user'] = $verification->getUser()->id();
    $verificationArray['video'] = $verification->getVideo()->id();
    $verificationJson = json_encode($verificationArray);
    file_put_contents('../private/verification_' . $verification->getUser()->id() . '.json', $verificationJson . PHP_EOL, FILE_APPEND);
    \Drupal::messenger()->addMessage($this->t('Verification archived.'));

    $verification->setVideo(NULL);
    $verification->setFirstname('');
    $verification->setLastname('');
    $verification->setBirthday(new DrupalDateTime('now', new \DateTimeZone('UTC')));
    $verification->setNationality('');
    $verification->setPlaceOfBirth('');
    $verification->setIdentityCardNumber('');
    $verification->setStreet('');
    $verification->setHousenumber('');
    $verification->setZip('');
    $verification->setCity('');
    $verification->save();
    $form_state->setRedirect('ohano_account.admin.account.verification');
  }

}
