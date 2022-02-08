<?php

namespace Drupal\ohano_account\Form;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Entity\EntityStorageException;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\ohano_account\Blocklist;
use Drupal\ohano_account\Entity\Account;
use Drupal\ohano_account\Entity\AccountActivation;
use Drupal\ohano_account\Entity\AccountVerification;
use Drupal\ohano_account\Validator\EmailValidator;
use Drupal\ohano_mail\OhanoMail;
use Drupal\ohano_mail\OhanoMailer;
use Drupal\user\Entity\User;
use PHPMailer\PHPMailer\Exception;

/**
 * Class providing the registration form.
 *
 * @package Drupal\ohano_account
 */
class RegistrationForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'ohano_account__registration';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = [];

    $form['info'] = [
      '#type' => 'markup',
      '#markup' => $this->t("This is the first of three steps you need to do. After you registered an account by submitting this form, you will be asked to verify your email address to activate your account. After that you also need to verify that you're over 18 years old by submitting a short video of yourself holding your ID card where we can clearly see your face, the photo and your birthday."),
    ];

    $form['username'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Username'),
      '#description' => $this->t('Choose a username between 4 and 24 characters. Your username may only contain latin letters as well as numbers and underscores (_).'),
      '#required' => TRUE,
      '#pattern' => '^[\w]{4,24}$',
    ];

    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Email address'),
      '#description' => $this->t('The email address we can contact you on.'),
      '#required' => TRUE,
    ];

    $form['password'] = [
      '#type' => 'password',
      '#title' => $this->t('Password'),
      '#description' => $this->t('Choose a strong password. We recommend using uppercase as well as lowercase letters, numbers and special chars. We also recommend using a password generator and a password manager.'),
      '#required' => TRUE,
    ];

    $form['password_repeat'] = [
      '#type' => 'password',
      '#title' => $this->t('Repeat password'),
      '#description' => $this->t('Repeat your chosen password.'),
      '#required' => TRUE,
    ];

    $form['other_text1'] = [
      '#type' => 'textfield',
      '#attributes' => [
        'style' => [
          'display: none;',
        ],
      ],
    ];

    $form['other_text2'] = [
      '#type' => 'textfield',
      '#attributes' => [
        'class' => [
          'visually-hidden',
        ],
      ],
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Register'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if (!empty($form_state->getValue('other_text1')) && !empty($form_state->getValue('other_text2'))) {
      $form_state->setErrorByName('submit', $this->t('Registration failed.'));
      return;
    }

    if ($form_state->getValue('password') != $form_state->getValue('password_repeat')) {
      $form_state->setErrorByName('password', $this->t("The passwords don't match."));
    }

    $existingUser = \Drupal::entityQuery('user')
      ->condition('name', $form_state->getValue('username'))
      ->execute();
    if (!empty($existingUser)) {
      $form_state->setErrorByName('username', $this->t("We're sorry but this username is already taken."));
    }

    /** @var \Drupal\ohano_account\Validator\EmailValidator $emailValidator */
    $emailValidator = \Drupal::service('ohano_account.validator.email');
    $emailValidity = $emailValidator->validateEmail($form_state->getValue('email'));

    switch ($emailValidity) {
      case EmailValidator::IN_USE:
        $form_state->setErrorByName('email', $this->t("We're sorry but this email address is already in use."));
        break;

      case EmailValidator::NOT_EMAIL:
        $form_state->setErrorByName('email', $this->t("Oops, this doesn't look like a valid email address."));
        break;
    }

    if (in_array(strtolower($form_state->getValue('username')), Blocklist::USERNAME)) {
      $form_state->setErrorByName('username', $this->t('The username you have chosen violates our username guidelines. Please choose another one.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $username = $form_state->getValue('username');
    $email = $form_state->getValue('email');
    $password = $form_state->getValue('password');

    /** @var \Drupal\user\Entity\User $user */
    $user = User::create();
    $user->setUsername($username);
    $user->setEmail($email);
    $user->setPassword($password);
    $user->enforceIsNew();

    try {
      $user->save();
    }
    catch (EntityStorageException $e) {
      \Drupal::messenger()->addError($this->t('Something went wrong when creating your account. Please try again.'));
      \Drupal::logger('ohano_account')->critical($e->getMessage());
      return;
    }

    try {
      Account::create()
        ->setUser($user)
        ->setBits(0)
        ->save();
    }
    catch (EntityStorageException $e) {
      \Drupal::messenger()->addError($this->t('Something went wrong when creating your account. Please try again.'));
      \Drupal::logger('ohano_account')->critical($e->getMessage());
      return;
    }

    $datetime = new DrupalDateTime("01-01-1970");

    $accountActivation = AccountActivation::create()
      ->setUsername($user->getAccountName())
      ->setEmail($user->getEmail())
      ->setCode(AccountActivation::generateRandomString(64))
      ->setActivatedOn($datetime)
      ->setIsValid(FALSE);
    try {
      $accountActivation->save();
    }
    catch (EntityStorageException $e) {
      \Drupal::messenger()->addError($this->t('Something went wrong when creating your account. Please try again.'));
      \Drupal::logger('ohano_account')->critical($e->getMessage());
      return;
    }

    $accountVerification = AccountVerification::create()
      ->setUser($user);
    try {
      $accountVerification->save();
    }
    catch (EntityStorageException $e) {
      \Drupal::messenger()->addError($this->t('Something went wrong when creating your account. Please try again.'));
      \Drupal::logger('ohano_account')->critical($e->getMessage());
      return;
    }

    $username = $accountActivation->getUsername();
    $code = $accountActivation->getCode();
    $host = \Drupal::request()->getHttpHost();
    $link = "https://$host/account/activate/$code";

    $mailer = new OhanoMailer(OhanoMail::AccountActivation);
    $mailer->renderBody([
      'username' => $username,
      'link' => $link,
    ]);
    $mailer->Subject = $this->t('Activate your account at ohano');

    try {
      $mailer->addAddress($accountActivation->getEmail(), $username);
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

    if (!empty($mailer->ErrorInfo)) {
      \Drupal::messenger()->addError($mailer->ErrorInfo);
      return;
    }

    \Drupal::messenger()->addMessage('Welcome to ohano! We have sent an email with instructions on how to activate your account.');
  }

}
