<?php

namespace Drupal\ohano_account\Entity;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Session\AccountInterface;
use Drupal\file\Entity\File;
use Drupal\ohano_core\Entity\EntityBase;

/**
 * Defines the AccountVerification entity.
 *
 * @package Drupal\ohano_account\Entity
 *
 * @ContentEntityType(
 *   id="account_verification",
 *   label=@Translation("Account Verification"),
 *   base_table="ohano_account_verification",
 *   entity_keys={
 *     "id" = "id",
 *     "uuid" = "uuid",
 *     "created" = "created",
 *     "updated" = "updated",
 *     "user" = "user",
 *     "video" = "video",
 *     "firstname" = "firstname",
 *     "lastname" = "lastname",
 *     "birthday" = "birthday",
 *     "nationality" = "nationality",
 *     "place_of_birth" = "place_of_birth",
 *     "identity_card_number" = "identity_card_number",
 *     "street" = "street",
 *     "housenumber" = "housenumber",
 *     "zip" = "zip",
 *     "city" = "city",
 *     "verified" = "verified",
 *     "last_comment" = "last_comment",
 *   },
 *   handlers = {
 *     "storage_schema" = "Drupal\ohano_account\Storage\Schema\AccountVerificationStorageSchema",
 *   }
 * )
 */
class AccountVerification extends EntityBase {

  const ENTITY_ID = 'account_verification';

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
      ->setSetting('handler', 'default')
      ->setRequired(TRUE)
      ->setCardinality(1);
    $fields['video'] = BaseFieldDefinition::create('entity_reference')
      ->setSetting('target_type', 'file')
      ->setSetting('handler', 'default')
      ->setRequired(TRUE)
      ->setCardinality(1);
    $fields['firstname'] = BaseFieldDefinition::create('string');
    $fields['lastname'] = BaseFieldDefinition::create('string');
    $fields['birthday'] = BaseFieldDefinition::create('datetime')
      ->setSetting('datetime_type', 'date');
    $fields['nationality'] = BaseFieldDefinition::create('string');
    $fields['place_of_birth'] = BaseFieldDefinition::create('string');
    $fields['identity_card_number'] = BaseFieldDefinition::create('string');
    $fields['street'] = BaseFieldDefinition::create('string');
    $fields['housenumber'] = BaseFieldDefinition::create('string');
    $fields['zip'] = BaseFieldDefinition::create('string');
    $fields['city'] = BaseFieldDefinition::create('string');
    $fields['verified'] = BaseFieldDefinition::create('boolean');
    $fields['last_comment'] = BaseFieldDefinition::create('string');

    return $fields;
  }

  /**
   * Gets the user.
   *
   * @return \Drupal\Core\Session\AccountInterface
   *   The user.
   */
  public function getUser(): AccountInterface {
    return $this->get('user')->entity;
  }

  /**
   * Gets the vide.
   *
   * @return \Drupal\file\Entity\File|null
   *   The vide.
   */
  public function getVideo(): ?File {
    return $this->get('video')->referencedEntities() ? $this->get('video')->referencedEntities()[0] : NULL;
  }

  /**
   * Gets the first name.
   *
   * @return string|null
   *   The first name.
   */
  public function getFirstname(): ?string {
    return $this->get('firstname')->value;
  }

  /**
   * Gets the last name.
   *
   * @return string|null
   *   The last name.
   */
  public function getLastname(): ?string {
    return $this->get('lastname')->value;
  }

  /**
   * Gets the birthday.
   *
   * @return \Drupal\Core\Datetime\DrupalDateTime|null
   *   The birthday.
   */
  public function getBirthday(): ?DrupalDateTime {
    return $this->get('birthday')->value ? DrupalDateTime::createFromFormat('U', $this->get('birthday')->value) : $this->get('birthday')->value;
  }

  /**
   * Gets the nationality.
   *
   * @return string|null
   *   The nationality.
   */
  public function getNationality(): ?string {
    return $this->get('nationality')->value;
  }

  /**
   * Gets the place of birth.
   *
   * @return string|null
   *   The place of birth.
   */
  public function getPlaceOfBirth(): ?string {
    return $this->get('place_of_birth')->value;
  }

  /**
   * Gets the identity card number.
   *
   * @return string|null
   *   The identity card number.
   */
  public function getIdentityCardNumber(): ?string {
    return $this->get('identity_card_number')->value;
  }

  /**
   * Gets the street.
   *
   * @return string|null
   *   The street.
   */
  public function getStreet(): ?string {
    return $this->get('street')->value;
  }

  /**
   * Gets the housenumber.
   *
   * @return string|null
   *   The housenumber.
   */
  public function getHousenumber(): ?string {
    return $this->get('housenumber')->value;
  }

  /**
   * Gets the zip.
   *
   * @return string|null
   *   The zip.
   */
  public function getZip(): ?string {
    return $this->get('zip')->value;
  }

  /**
   * Gets the city.
   *
   * @return string|null
   *   The city.
   */
  public function getCity(): ?string {
    return $this->get('city')->value;
  }

  /**
   * Gets if the user is already verifiedt.
   *
   * @return bool|null
   *   TRUE if the user is verified.
   */
  public function isVerified(): ?bool {
    return (bool) $this->get('verified')->value;
  }

  /**
   * Gets the last comment.
   *
   * @return string|null
   *   The last comment.
   */
  public function getLastComment(): ?string {
    return $this->get('last_comment')->value;
  }

  /**
   * Sets the user.
   *
   * @param \Drupal\Core\Session\AccountInterface $user
   *   The user to set.
   *
   * @return \Drupal\ohano_account\Entity\AccountVerification
   *   The active instance of this class.
   */
  public function setUser(AccountInterface $user): AccountVerification {
    $this->set('user', $user);
    return $this;
  }

  /**
   * Sets the video.
   *
   * @param \Drupal\file\Entity\File|null $video
   *   The video to set.
   *
   * @return \Drupal\ohano_account\Entity\AccountVerification
   *   The active instance of this class.
   */
  public function setVideo(File $video = NULL): AccountVerification {
    $this->set('video', $video);
    return $this;
  }

  /**
   * Sets the firstname.
   *
   * @param string $firstname
   *   The firstname to set.
   *
   * @return \Drupal\ohano_account\Entity\AccountVerification
   *   The active instance of this class.
   */
  public function setFirstname(string $firstname): AccountVerification {
    $this->set('firstname', $firstname);
    return $this;
  }

  /**
   * Sets the lastname.
   *
   * @param string $lastname
   *   The lastname to set.
   *
   * @return \Drupal\ohano_account\Entity\AccountVerification
   *   The active instance of this class.
   */
  public function setLastname(string $lastname): AccountVerification {
    $this->set('lastname', $lastname);
    return $this;
  }

  /**
   * Sets the birthday.
   *
   * @param \Drupal\Core\Datetime\DrupalDateTime $birthday
   *   The birthday to set.
   *
   * @return \Drupal\ohano_account\Entity\AccountVerification
   *   The active instance of this class.
   */
  public function setBirthday(DrupalDateTime $birthday): AccountVerification {
    $this->set('birthday', $birthday->format('U'));
    return $this;
  }

  /**
   * Sets the nationality.
   *
   * @param string $nationality
   *   The nationality to set.
   *
   * @return \Drupal\ohano_account\Entity\AccountVerification
   *   The active instance of this class.
   */
  public function setNationality(string $nationality): AccountVerification {
    $this->set('nationality', $nationality);
    return $this;
  }

  /**
   * Sets the place of birth.
   *
   * @param string $placeOfBirth
   *   The place of birth to set.
   *
   * @return \Drupal\ohano_account\Entity\AccountVerification
   *   The active instance of this class.
   */
  public function setPlaceOfBirth(string $placeOfBirth): AccountVerification {
    $this->set('place_of_birth', $placeOfBirth);
    return $this;
  }

  /**
   * Sets the identity card number.
   *
   * @param string $identityCardNumber
   *   The ID card number to set.
   *
   * @return \Drupal\ohano_account\Entity\AccountVerification
   *   The active instance of this class.
   */
  public function setIdentityCardNumber(string $identityCardNumber): AccountVerification {
    $this->set('identity_card_number', $identityCardNumber);
    return $this;
  }

  /**
   * Sets the street.
   *
   * @param string $street
   *   The street to set.
   *
   * @return \Drupal\ohano_account\Entity\AccountVerification
   *   The active instance of this class.
   */
  public function setStreet(string $street): AccountVerification {
    $this->set('street', $street);
    return $this;
  }

  /**
   * Sets the house number.
   *
   * @param string $housenumber
   *   The house number to set.
   *
   * @return \Drupal\ohano_account\Entity\AccountVerification
   *   The active instance of this class.
   */
  public function setHousenumber(string $housenumber): AccountVerification {
    $this->set('housenumber', $housenumber);
    return $this;
  }

  /**
   * Sets the zip.
   *
   * @param string $zip
   *   The zip to set.
   *
   * @return \Drupal\ohano_account\Entity\AccountVerification
   *   The active instance of this class.
   */
  public function setZip(string $zip): AccountVerification {
    $this->set('zip', $zip);
    return $this;
  }

  /**
   * Sets the city.
   *
   * @param string $city
   *   The city to set.
   *
   * @return \Drupal\ohano_account\Entity\AccountVerification
   *   The active instance of this class.
   */
  public function setCity(string $city): AccountVerification {
    $this->set('city', $city);
    return $this;
  }

  /**
   * Sets the verification status.
   *
   * @param bool $isVerified
   *   Whether the user is verified.
   *
   * @return \Drupal\ohano_account\Entity\AccountVerification
   *   The active instance of this class.
   */
  public function setIsVerified(bool $isVerified): AccountVerification {
    $this->set('verified', $isVerified);
    return $this;
  }

  /**
   * Sets the last comment.
   *
   * @param string $lastComment
   *   The comment to set as last comment.
   *
   * @return \Drupal\ohano_account\Entity\AccountVerification
   *   The active instance of this class.
   */
  public function setLastComment(string $lastComment): AccountVerification {
    $this->set('last_comment', $lastComment);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function render(): array {
    return parent::render() + [
      'user' => $this->getUser(),
      'video' => $this->getVideo(),
      'firstname' => $this->getFirstname(),
      'lastname' => $this->getLastname(),
      'birthday' => $this->getBirthday(),
      'nationality' => $this->getNationality(),
      'place_of_birth' => $this->getPlaceOfBirth(),
      'identity_card_number' => $this->getIdentityCardNumber(),
      'street' => $this->getStreet(),
      'housenumber' => $this->getHousenumber(),
      'zip' => $this->getZip(),
      'city' => $this->getCity(),
      'verified' => $this->isVerified(),
      'last_comment' => $this->getLastComment(),
    ];
  }

}
