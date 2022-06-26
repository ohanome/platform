<?php

namespace Drupal\ohano_account\Form\Config;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Registration configuration form.
 *
 * @package Drupal\ohano_account\Form\Config
 */
class RegistrationConfigForm extends ConfigFormBase {

  const CONFIG_NAME = 'ohano_account.registration';

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames(): array {
    return [
      self::CONFIG_NAME,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'ohano_account_config_registration';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $form = parent::buildForm($form, $form_state);
    $config = $this->configFactory()->get(self::CONFIG_NAME);

    $form['open'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Open'),
      '#description' => $this->t('Whether the registration should be open or not.'),
      '#default_value' => $config->get('open'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $formValues = $form_state->getValues();
    $config = $this->configFactory()->getEditable(self::CONFIG_NAME);

    $config->set('open', $formValues['open']);
    $config->save();
    parent::submitForm($form, $form_state);
  }

}
