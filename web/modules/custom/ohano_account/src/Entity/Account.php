<?php

namespace Drupal\ohano_account\Entity;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Session\AccountInterface;
use Drupal\ohano_core\Entity\EntityBase;
use Drupal\ohano_core\Entity\EntityInterface;
use Drupal\ohano_profile\Entity\UserProfile;

/**
 * Defines the Account entity.
 *
 * @package Drupal\ohano_account\Entity
 *
 * @ContentEntityType(
 *   id = "account",
 *   label = @Translation("Account"),
 *   base_table = "ohano_account",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *     "created" = "created",
 *     "updated" = "updated",
 *     "user" = "user",
 *     "bits" = "bits",
 *     "active_profile" = "active_profile",
 *   }
 * )
 */
class Account extends EntityBase implements EntityInterface {

  const ENTITY_ID = 'account';

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

    $fields['user'] = BaseFieldDefinition::create('entity_reference')
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default');
    $fields['bits'] = BaseFieldDefinition::create('integer');
    $fields['active_profile'] = BaseFieldDefinition::create('entity_reference')
      ->setSetting('target_type', 'user_profile')
      ->setSetting('handler', 'default');

    return $fields;
  }

  /**
   * Gets the user entity.
   *
   * @return \Drupal\Core\Session\AccountInterface
   *   The user entity for this account.
   */
  public function getUser(): AccountInterface {
    return $this->get('user')->entity;
  }

  /**
   * Gets the bit account balance.
   *
   * @return int
   *   The bits of the user.
   */
  public function getBits(): int {
    return $this->get('bits')->value;
  }

  public function getActiveProfile(): UserProfile {
    return $this->get('active_profile')->entity;
  }

  /**
   * Sets the user for this account.
   *
   * @param \Drupal\Core\Session\AccountInterface $user
   *   The user to set.
   *
   * @return \Drupal\ohano_account\Entity\Account
   *   The active instance of this class.
   */
  public function setUser(AccountInterface $user): Account {
    $this->set('user', $user);
    return $this;
  }

  /**
   * Sets the bit balance.
   *
   * @param int $bits
   *   The bits to set.
   *
   * @return \Drupal\ohano_account\Entity\Account
   *   The active instance of this class.
   */
  public function setBits(int $bits): Account {
    $this->set('bits', $bits);
    return $this;
  }

  public function setActiveProfile(UserProfile $userProfile): Account {
    $this->set('active_profile', $userProfile);
    return $this;
  }

  /**
   * Raises the balance by the given amount.
   *
   * @param int $bits
   *   The amount of bits to add.
   *
   * @return \Drupal\ohano_account\Entity\Account
   *   The active instance of this class.
   */
  public function addBits(int $bits): Account {
    return $this->setBits($this->getBits() + $bits);
  }

  /**
   * Lowers the balance by the given amount.
   *
   * @param int $bits
   *   The amount of bits to subtract.
   *
   * @return \Drupal\ohano_account\Entity\Account
   *   The active instance of this class.
   */
  public function subtractBits(int $bits): Account {
    return $this->setBits($this->getBits() - $bits);
  }

  /**
   * Gets an account entity for the given user.
   *
   * @param \Drupal\Core\Session\AccountInterface $user
   *   The user to fetch the account entity from.
   *
   * @return \Drupal\ohano_account\Entity\Account|null
   *   The account entity.
   */
  public static function getByUser(AccountInterface $user): ?Account {
    $accountId = \Drupal::entityQuery('account')
      ->condition('user', $user->id())
      ->execute();

    return Account::load(array_values($accountId)[0]);
  }

  /**
   * Gets the account entity for the active user.
   *
   * @return \Drupal\ohano_account\Entity\Account|null
   *   The account entity.
   */
  public static function forActive(): ?Account {
    return Account::getByUser(\Drupal::currentUser());
  }

}
