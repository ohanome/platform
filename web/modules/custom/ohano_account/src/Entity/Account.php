<?php

namespace Drupal\ohano_account\Entity;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Session\AccountInterface;
use Drupal\ohano_core\Entity\EntityBase;
use Drupal\ohano_core\Entity\EntityInterface;

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

}
