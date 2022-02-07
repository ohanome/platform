<?php

namespace Drupal\ohano_account\Entity;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\ohano_core\Entity\EntityBase;

/**
 * Defines the UserActivation entity.
 *
 * @package Drupal\ohano_account\Entity
 *
 * @ContentEntityType(
 *   id = "account_activation",
 *   label = @Translation("Account Activation"),
 *   base_table = "ohano_account_activation",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *     "created" = "created",
 *     "updated" = "updated",
 *     "username" = "username",
 *     "email" = "email",
 *     "is_valid" = "is_valid",
 *     "activated_on" = "activated_on",
 *     "code" = "code",
 *   }
 * )
 */
class AccountActivation extends EntityBase {

  const ENTITY_ID = 'account_activation';

  /**
   * {@inheritdoc}
   */
  public static function entityTypeId(): string {
    return self::ENTITY_ID;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['username'] = BaseFieldDefinition::create('string');
    $fields['email'] = BaseFieldDefinition::create('string');
    $fields['is_valid'] = BaseFieldDefinition::create('boolean');
    $fields['activated_on'] = BaseFieldDefinition::create('datetime');
    $fields['code'] = BaseFieldDefinition::create('string');

    return $fields;
  }

  /**
   * Generates a random string.
   *
   * @return string
   *   The random string.
   */
  public static function generateRandomString($length = 10): string {
    return substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))), 1, $length);
  }

  /**
   * Gets the username.
   *
   * @return string
   *   The username.
   */
  public function getUsername(): string {
    return $this->get('username')->value;
  }

  /**
   * Gets the email.
   *
   * @return string
   *   The email.
   */
  public function getEmail(): string {
    return $this->get('email')->value;
  }

  /**
   * Gets if the activation is valid.
   *
   * @return bool
   *   TRUE if valid, FALSE if not.
   */
  public function isValid(): bool {
    return (bool) $this->get('is_valid')->value;
  }

  /**
   * Gets the time when the activation was completed.
   *
   * @return int
   *   The activation time as integer timestamp.
   */
  public function getActivatedOn(): int {
    return $this->get('activated_on')->value;
  }

  /**
   * Gets the activation code.
   *
   * @return string
   *   The activation code.
   */
  public function getCode(): string {
    return $this->get('code')->value;
  }

  /**
   * Sets the username.
   *
   * @param string $username
   *   The username to set.
   *
   * @return $this
   *   The active instance of this object.
   */
  public function setUsername(string $username): AccountActivation {
    $this->set('username', $username);
    return $this;
  }

  /**
   * Sets the email.
   *
   * @param string $email
   *   The email address to set.
   *
   * @return $this
   *   The active instance of this object.
   */
  public function setEmail(string $email): AccountActivation {
    $this->set('email', $email);
    return $this;
  }

  /**
   * Sets whether the activation is valid.
   *
   * @param bool $isValid
   *   Whether the activation is valid.
   *
   * @return $this
   *   The active instance of this object.
   */
  public function setIsValid(bool $isValid): AccountActivation {
    $this->set('is_valid', (int) $isValid);
    return $this;
  }

  /**
   * Sets the activation time.
   *
   * @param \Drupal\Core\Datetime\DrupalDateTime $activatedOn
   *   The time this activation was completed.
   *
   * @return $this
   *   The active instance of this object.
   */
  public function setActivatedOn(DrupalDateTime $activatedOn): AccountActivation {
    $this->set('activated_on', $activatedOn->format('U'));
    return $this;
  }

  /**
   * Sets the activation code.
   *
   * @param string $code
   *   The activation code to set.
   *
   * @return $this
   *   The active instance of this object.
   */
  public function setCode(string $code): AccountActivation {
    $this->set('code', $code);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function render(): array {
    return parent::render() + [
      'username' => $this->getUsername(),
      'email' => $this->getEmail(),
      'is_valid' => $this->isValid(),
      'activated_on' => $this->getActivatedOn(),
      'code' => $this->getCode(),
    ];
  }

  /**
   * Sets the entity as activated.
   *
   * @return \Drupal\ohano_account\Entity\AccountActivation
   *   The active instance of this class.
   */
  public function activate(): AccountActivation {
    $this->setIsValid(TRUE)
      ->setActivatedOn(new DrupalDateTime());
    return $this;
  }

  /**
   * Finds an entity by the given code.
   *
   * @return \Drupal\ohano_account\Entity\AccountActivation
   *   The found entity.
   *
   * @throws \Exception
   */
  public static function findByCode(string $code): AccountActivation {
    $activation = \Drupal::entityQuery('account_activation')
      ->condition('code', $code)
      ->execute();

    if (empty($activation)) {
      throw new \Exception("The account_activation entity for the code $code could not be found.");
    }

    return AccountActivation::load(array_values($activation)[0]);
  }

}
