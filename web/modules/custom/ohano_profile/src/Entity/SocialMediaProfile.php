<?php

namespace Drupal\ohano_profile\Entity;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use http\Exception\InvalidArgumentException;

/**
 * Defines the SocialProfile entity.
 *
 * @package Drupal\ohano_profile\Entity
 *
 * @noinspection PhpUnused
 *
 * @ContentEntityType(
 *   id = "social_media_profile",
 *   label = @Translation("Social media profile"),
 *   base_table = "ohano_social_media_profile",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *     "created" = "created",
 *     "updated" = "updated",
 *     "profile" = "profile",
 *     "twitter" = "twitter",
 *     "instagram" = "instagram",
 *     "facebook" = "facebook",
 *     "linkedin" = "linkedin",
 *     "xing" = "xing",
 *     "pinterest" = "pinterest",
 *     "discord" = "discord",
 *     "behance" = "behance",
 *     "dribbble" = "dribbble",
 *   },
 *   handlers = {
 *     "storage_schema" = "Drupal\ohano_profile\Storage\Schema\SubProfileStorageSchema",
 *   }
 * )
 */
class SocialMediaProfile extends SubProfileBase {

  const ENTITY_ID = 'social_media_profile';

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

    $fields['twitter'] = BaseFieldDefinition::create('string');
    $fields['instagram'] = BaseFieldDefinition::create('string');
    $fields['facebook'] = BaseFieldDefinition::create('string');
    $fields['linkedin'] = BaseFieldDefinition::create('string');
    $fields['xing'] = BaseFieldDefinition::create('string');
    $fields['pinterest'] = BaseFieldDefinition::create('string');
    $fields['discord'] = BaseFieldDefinition::create('string');
    $fields['behance'] = BaseFieldDefinition::create('string');
    $fields['dribbble'] = BaseFieldDefinition::create('string');

    return $fields;
  }

  /**
   * Gets the Twitter username.
   *
   * @return string|null
   *   The Twitter username.
   */
  public function getTwitter(): ?string {
    return $this->get('twitter')->value;
  }

  /**
   * Gets the Instagram username.
   *
   * @return string|null
   *   The Instagram username.
   */
  public function getInstagram(): ?string {
    return $this->get('instagram')->value;
  }

  /**
   * Gets the Facebook username.
   *
   * @return string|null
   *   The Facebook username.
   */
  public function getFacebook(): ?string {
    return $this->get('facebook')->value;
  }

  /**
   * Gets the LinkedIn username.
   *
   * @return string|null
   *   The LinkedIn username.
   */
  public function getLinkedin(): ?string {
    return $this->get('linkedin')->value;
  }

  /**
   * Gets the Xing username.
   *
   * @return string|null
   *   The Xing username.
   */
  public function getXing(): ?string {
    return $this->get('xing')->value;
  }

  /**
   * Gets the Pinterest username.
   *
   * @return string|null
   *   The Pinterest username.
   */
  public function getPinterest(): ?string {
    return $this->get('pinterest')->value;
  }

  /**
   * Gets the Discord username.
   *
   * @return string|null
   *   The Discord username.
   */
  public function getDiscord(): ?string {
    return $this->get('discord')->value;
  }

  /**
   * Gets the Behance username.
   *
   * @return string|null
   *   The Behance username.
   */
  public function getBehance(): ?string {
    return $this->get('behance')->value;
  }

  /**
   * Gets the Dribbble username.
   *
   * @return string|null
   *   The Dribbble username.
   */
  public function getDribbble(): ?string {
    return $this->get('dribbble')->value;
  }

  /**
   * Sets the Twitter username.
   *
   * @param string|null $twitter
   *   The Twitter username to set.
   *
   * @return \Drupal\ohano_profile\Entity\SocialMediaProfile
   *   The active instance of this class.
   */
  public function setTwitter(string $twitter = NULL): SocialMediaProfile {
    $this->set('twitter', $twitter);
    return $this;
  }

  /**
   * Sets the Instagram username.
   *
   * @param string|null $instagram
   *   The Instagram username to set.
   *
   * @return \Drupal\ohano_profile\Entity\SocialMediaProfile
   *   The active instance of this class.
   */
  public function setInstagram(string $instagram = NULL): SocialMediaProfile {
    $this->set('instagram', $instagram);
    return $this;
  }

  /**
   * Sets the Facebook username.
   *
   * @param string|null $facebook
   *   The Facebook username to set.
   *
   * @return \Drupal\ohano_profile\Entity\SocialMediaProfile
   *   The active instance of this class.
   */
  public function setFacebook(string $facebook = NULL): SocialMediaProfile {
    $this->set('facebook', $facebook);
    return $this;
  }

  /**
   * Sets the LinkedIn username.
   *
   * @param string|null $linkedin
   *   The LinkedIn username to set.
   *
   * @return \Drupal\ohano_profile\Entity\SocialMediaProfile
   *   The active instance of this class.
   */
  public function setLinkedin(string $linkedin = NULL): SocialMediaProfile {
    $this->set('linkedin', $linkedin);
    return $this;
  }

  /**
   * Sets the Xing username.
   *
   * @param string|null $xing
   *   The Xing username to set.
   *
   * @return \Drupal\ohano_profile\Entity\SocialMediaProfile
   *   The active instance of this class.
   */
  public function setXing(string $xing = NULL): SocialMediaProfile {
    $this->set('xing', $xing);
    return $this;
  }

  /**
   * Sets the Pinterest username.
   *
   * @param string|null $pinterest
   *   The Pinterest username to set.
   *
   * @return \Drupal\ohano_profile\Entity\SocialMediaProfile
   *   The active instance of this class.
   */
  public function setPinterest(string $pinterest = NULL): SocialMediaProfile {
    $this->set('pinterest', $pinterest);
    return $this;
  }

  /**
   * Sets the Discord username.
   *
   * @param string|null $discord
   *   The Discord username to set.
   *
   * @return \Drupal\ohano_profile\Entity\SocialMediaProfile
   *   The active instance of this class.
   */
  public function setDiscord(string $discord = NULL): SocialMediaProfile {
    $this->set('discord', $discord);
    return $this;
  }

  /**
   * Sets the Behance username.
   *
   * @param string|null $behance
   *   The Behance username to set.
   *
   * @return \Drupal\ohano_profile\Entity\SocialMediaProfile
   *   The active instance of this class.
   */
  public function setBehance(string $behance = NULL): SocialMediaProfile {
    $this->set('behance', $behance);
    return $this;
  }

  /**
   * Sets the Dribbble username.
   *
   * @param string|null $dribbble
   *   The Dribbble username to set.
   *
   * @return \Drupal\ohano_profile\Entity\SocialMediaProfile
   *   The active instance of this class.
   */
  public function setDribbble(string $dribbble = NULL): SocialMediaProfile {
    $this->set('dribbble', $dribbble);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function render(): array {
    return parent::render() + [
      'twitter' => $this->getTwitter(),
      'instagram' => $this->getInstagram(),
      'facebook' => $this->getFacebook(),
      'linkedin' => $this->getLinkedin(),
      'xing' => $this->getXing(),
      'pinterest' => $this->getPinterest(),
      'discord' => $this->getDiscord(),
      'behance' => $this->getBehance(),
      'dribbble' => $this->getDribbble(),
    ];
  }

}
