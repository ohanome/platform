<?php

namespace Drupal\ohano_account\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\user\Form\UserLoginForm;

/**
 * Login form that extends the core login form.
 *
 * @package Drupal\ohano_account\Form
 */
class LoginForm extends UserLoginForm {

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'ohano_account_login';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $form = parent::buildForm($form, $form_state);

    unset($form['name']['#description']);
    unset($form['pass']['#description']);

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->messenger()->addMessage($this->t('Welcome back!'));

    $form_state->setRedirect('<front>');
  }

}
