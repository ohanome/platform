<?php

namespace Drupal\ohano_account\Entity;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Session\AccountInterface;
use Drupal\ohano_core\Entity\EntityBase;

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
class Account extends EntityBase {

  const ENTITY_ID = 'account';

  public static function entityTypeId(): string {
    return self::ENTITY_ID;
  }

  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['user'] = BaseFieldDefinition::create('entity_reference')
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default');
    $fields['bits'] = BaseFieldDefinition::create('integer');

    return $fields;
  }

  public function getUser(): AccountInterface {
    return $this->get('user')->entity;
  }

  public function getBits(): int {
    return $this->get('bits')->value;
  }

  public function setUser(AccountInterface $user): Account {
    $this->set('user', $user);
    return $this;
  }

  public function setBits(int $bits): Account {
    $this->set('bits', $bits);
    return $this;
  }

  public function addBits(int $bits): Account {
    return $this->setBits($this->getBits() + $bits);
  }

  public function subtractBits(int $bits): Account {
    return $this->setBits($this->getBits() - $bits);
  }

}
