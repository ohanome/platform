<?php

namespace Drupal\ohano_profile\Entity;

use Drupal\Core\Session\AccountInterface;
use Drupal\ohano_account\Entity\Account;
use Drupal\ohano_core\Entity\EntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;

/**
 * Defines the UserProfile entity.
 *
 * @package Drupal\ohano_profile\Entity
 *
 * @ContentEntityType(
 *   id = "user_profile",
 *   label = @Translation("User profile"),
 *   base_table = "ohano_user_profile",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *     "created" = "created",
 *     "updated" = "updated",
 *     "status" = "status",
 *     "account" = "account",
 *     "exclude_from_search" = "exclude_from_search",
 *   }
 * )
 */
class UserProfile extends EntityBase {

  const ENTITY_ID = 'user_profile';

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

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setDefaultValue(1);

    $fields['account'] = BaseFieldDefinition::create('entity_reference')
      ->setSetting('target_type', 'account')
      ->setSetting('handler', 'default')
      ->setCardinality(1);

    $fields['exclude_from_search'] = BaseFieldDefinition::create('boolean')
      ->setDefaultValue(0);

    return $fields;
  }

  /**
   * Gets the status.
   *
   * @return bool
   *   TRUE id unlocked, FALSE if locked.
   */
  public function getStatus(): bool {
    return (bool) $this->get('status')->value;
  }

  /**
   * Gets the account.
   *
   * @return \Drupal\ohano_account\Entity\Account
   *   The account.
   */
  public function getAccount(): Account {
    return $this->get('account')->referencedEntities()[0];
  }

  /**
   * Gets if the profile is excluded from search.
   *
   * @return bool
   *   TRUE if the profile is excluded from search.
   */
  public function isExcludedFromSearch(): bool {
    return (bool) $this->get('exclude_from_search')->value;
  }

  /**
   * Sets the status.
   *
   * @param bool $status
   *   The status to set.
   *
   * @return \Drupal\ohano_profile\Entity\UserProfile
   *   The active instance of this class.
   */
  public function setStatus(bool $status): UserProfile {
    $this->set('status', (int) $status);
    return $this;
  }

  /**
   * Sets the account.
   *
   * @param \Drupal\ohano_account\Entity\Account $account
   *   The account to set.
   *
   * @return \Drupal\ohano_profile\Entity\UserProfile
   *   The active instance of this class.
   */
  public function setAccount(Account $account): UserProfile {
    $this->set('account', $account);
    return $this;
  }

  /**
   * Sets if the profile cannot be found over search.
   *
   * @param bool $excludedFromSearch
   *   TRUE if the profile should be hidden from search.
   *
   * @return \Drupal\ohano_profile\Entity\UserProfile
   *   The active instance of this class.
   */
  public function setIsExcludedFromSearch(bool $excludedFromSearch): UserProfile {
    $this->set('exclude_from_search', (int) $excludedFromSearch);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function render(): array {
    return parent::render() + [
        'status' => $this->getStatus(),
        'account' => $this->getAccount(),
        'exclude_from_search' => $this->isExcludedFromSearch()
      ];
  }

  /**
   * @param \Drupal\Core\Session\AccountInterface $user
   *
   * @return \Drupal\ohano_profile\Entity\UserProfile|null
   */
  public static function loadByUser(AccountInterface $user): ?UserProfile {
    $account = Account::getByUser($user);
    if (empty($account)) {
      return NULL;
    }

    $userProfileId = \Drupal::entityQuery(UserProfile::ENTITY_ID)
      ->condition('account', $account->id())
      ->execute();
    return empty($userProfileId) ? NULL : UserProfile::load(array_values($userProfileId)[0]);
  }

}
