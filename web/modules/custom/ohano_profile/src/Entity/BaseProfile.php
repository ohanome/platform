<?php

namespace Drupal\ohano_profile\Entity;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\file\Entity\File;
use Drupal\ohano_profile\Option\Gender;

/**
 * Defines the BaseProfile entity.
 *
 * @package Drupal\ohano_profile\Entity
 *
 * @ContentEntityType(
 *   id = "base_profile",
 *   label = @Translation("Base profile"),
 *   base_table = "ohano_base_profile",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *     "created" = "created",
 *     "updated" = "updated",
 *     "profile" = "profile",
 *     "username" = "username",
 *     "realname" = "realname",
 *     "profile_picture" = "profile_picture",
 *     "profile_banner" = "profile_banner",
 *     "profile_text" = "profile_text",
 *     "birthday" = "birthday",
 *     "gender" = "gender",
 *     "city" = "city",
 *     "province" = "province",
 *     "country" = "country",
 *   },
 *   handlers = {
 *     "storage_schema" = "Drupal\ohano_profile\Storage\Schema\SubProfileStorageSchema",
 *   }
 * )
 */
class BaseProfile extends SubProfileBase {

  use StringTranslationTrait;

  const ENTITY_ID = 'base_profile';

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
    $fields['realname'] = BaseFieldDefinition::create('string');
    $fields['profile_picture'] = BaseFieldDefinition::create('entity_reference')
      ->setSetting('target_type', 'file')
      ->setSetting('handler', 'default')
      ->setCardinality(1);
    $fields['profile_banner'] = BaseFieldDefinition::create('entity_reference')
      ->setSetting('target_type', 'file')
      ->setSetting('handler', 'default')
      ->setCardinality(1);
    $fields['profile_text'] = BaseFieldDefinition::create('string_long');
    $fields['birthday'] = BaseFieldDefinition::create('datetime')
      ->setSetting('datetime_type', 'date');
    $fields['gender'] = BaseFieldDefinition::create('list_string')
      ->setSetting('allowed_values', Gender::translatableFormOptions());
    $fields['city'] = BaseFieldDefinition::create('string');
    $fields['province'] = BaseFieldDefinition::create('string');
    $fields['country'] = BaseFieldDefinition::create('string');

    return $fields;
  }

  /**
   * Gets the username.
   *
   * @return string|null
   *   The username.
   */
  public function getUsername(): ?string {
    return $this->get('username')->value;
  }

  /**
   * Gets the real name.
   *
   * @return string|null
   *   The real name of the user.
   */
  public function getRealname(): ?string {
    return $this->get('realname')->value;
  }

  /**
   * Gets the profile picture as File entity.
   *
   * @return \Drupal\file\Entity\File|null
   *   The profile picture as entity.
   */
  public function getProfilePicture(): ?File {
    return $this->get('profile_picture')->referencedEntities()[0];
  }

  /**
   * Gets the profile banner as File entity.
   *
   * @return \Drupal\file\Entity\File|null
   *   The profile banner as entity.
   */
  public function getProfileBanner(): ?File {
    return $this->get('profile_banner')->referencedEntities()[0];
  }

  /**
   * Gets the profile text.
   *
   * @return string|null
   *   The profile text as plain string.
   */
  public function getProfileText(): ?string {
    return $this->get('profile_text')->value;
  }

  /**
   * Gets the birthday.
   *
   * @return int|null
   *   The birthday as timestamp.
   */
  public function getBirthday(): ?int {
    return $this->get('birthday')->value;
  }

  /**
   * Gets the gender of the person.
   *
   * @return \Drupal\ohano_profile\Option\Gender|null
   *   The gender as gender key.
   */
  public function getGender(): ?Gender {
    $gender = $this->get('gender')->value;
    foreach (Gender::cases() as $case) {
      if ($case->value == $gender) {
        return $case;
      }
    }

    return NULL;
  }

  /**
   * Gets the city the user lives in.
   *
   * @return string|null
   *   The city the user lives in.
   */
  public function getCity(): ?string {
    return $this->get('city')->value;
  }

  /**
   * Gets the province the user lives in.
   *
   * @return string|null
   *   The province the user lives in.
   */
  public function getProvince(): ?string {
    return $this->get('province')->value;
  }

  /**
   * Gets the country the user lives in.
   *
   * @return string|null
   *   The country the user lives in.
   */
  public function getCountry(): ?string {
    return $this->get('country')->value;
  }

  /**
   * Sets the username.
   *
   * @param string $username
   *   The username to set.
   *
   * @return \Drupal\ohano_profile\Entity\BaseProfile
   *   The active instance of this class.
   */
  public function setUsername(string $username): BaseProfile {
    $this->set('username', $username);
    return $this;
  }

  /**
   * Sets the real name of the user.
   *
   * @param string $realname
   *   The real name to set.
   *
   * @return \Drupal\ohano_profile\Entity\BaseProfile
   *   The active instance of this class.
   *
   * @noinspection PhpUnused
   */
  public function setRealname(string $realname): BaseProfile {
    $this->set('realname', $realname);
    return $this;
  }

  /**
   * Sets the profile picture.
   *
   * @param \Drupal\file\Entity\File $profilePicture
   *   The profile picture to set.
   *
   * @return \Drupal\ohano_profile\Entity\BaseProfile
   *   The active instance of this class.
   *
   * @noinspection PhpUnused
   */
  public function setProfilePicture(File $profilePicture): BaseProfile {
    $this->set('profile_picture', $profilePicture);
    return $this;
  }

  /**
   * Sets the profile text.
   *
   * @param string $profileText
   *   The profile text to set.
   *
   * @return \Drupal\ohano_profile\Entity\BaseProfile
   *   The active instance of this class.
   *
   * @noinspection PhpUnused
   */
  public function setProfileText(string $profileText): BaseProfile {
    $this->set('profile_text', $profileText);
    return $this;
  }

  /**
   * Sets the birthday.
   *
   * @param \Drupal\Core\Datetime\DrupalDateTime $birthday
   *   The birthday to set as timestamp int.
   *
   * @return \Drupal\ohano_profile\Entity\BaseProfile
   *   The active instance of this class.
   *
   * @noinspection PhpUnused
   */
  public function setBirthday(DrupalDateTime $birthday): BaseProfile {
    $this->set('birthday', $birthday->format('U'));
    return $this;
  }

  /**
   * Sets the gender.
   *
   * @param \Drupal\ohano_profile\Option\Gender $gender
   *   The gender to set.
   *
   * @return \Drupal\ohano_profile\Entity\BaseProfile
   *   The active instance of this class.
   *
   * @noinspection PhpUnused
   */
  public function setGender(Gender $gender): BaseProfile {
    $this->set('gender', $gender->value);
    return $this;
  }

  /**
   * Sets the city.
   *
   * @param string $city
   *   The city to set.
   *
   * @return \Drupal\ohano_profile\Entity\BaseProfile
   *   The active instance of this class.
   */
  public function setCity(string $city): BaseProfile {
    $this->set('city', $city);
    return $this;
  }

  /**
   * Sets the province.
   *
   * @param string $province
   *   The province to set.
   *
   * @return \Drupal\ohano_profile\Entity\BaseProfile
   *   The active instance of this class.
   */
  public function setProvince(string $province): BaseProfile {
    $this->set('province', $province);
    return $this;
  }

  /**
   * Sets the country.
   *
   * @param string $country
   *   The country to set.
   *
   * @return \Drupal\ohano_profile\Entity\BaseProfile
   *   The active instance of this class.
   */
  public function setCountry(string $country): BaseProfile {
    $this->set('country', $country);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function render(): array {
    return parent::render() + [
      'username' => $this->getUsername(),
      'realname' => $this->getRealname(),
      'profile_picture' => $this->getProfilePicture(),
      'profile_picture_url' => $this->getProfilePicture()->createFileUrl(FALSE),
      'profile_banner' => $this->getProfileBanner(),
      'profile_banner_url' => $this->getProfileBanner()->createFileUrl(FALSE),
      'profile_text' => $this->getProfileText(),
      'birthday' => $this->getBirthday(),
      'gender' => $this->getGender(),
      'city' => $this->getCity(),
      'province' => $this->getProvince(),
      'country' => $this->getCountry(),
    ];
  }

}
