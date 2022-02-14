<?php

namespace Drupal\ohano_profile\Entity;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;

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
   * @param string $twitter
   *   The Twitter username to set.
   *
   * @return \Drupal\ohano_profile\Entity\SocialMediaProfile
   *   The active instance of this class.
   */
  public function setTwitter(string $twitter): SocialMediaProfile {
    $this->set('twitter', $twitter);
    return $this;
  }

  /**
   * Sets the Instagram username.
   *
   * @param string $instagram
   *   The Instagram username to set.
   *
   * @return \Drupal\ohano_profile\Entity\SocialMediaProfile
   *   The active instance of this class.
   */
  public function setInstagram(string $instagram): SocialMediaProfile {
    $this->set('instagram', $instagram);
    return $this;
  }

  /**
   * Sets the Facebook username.
   *
   * @param string $facebook
   *   The Facebook username to set.
   *
   * @return \Drupal\ohano_profile\Entity\SocialMediaProfile
   *   The active instance of this class.
   */
  public function setFacebook(string $facebook): SocialMediaProfile {
    $this->set('facebook', $facebook);
    return $this;
  }

  /**
   * Sets the LinkedIn username.
   *
   * @param string $linkedin
   *   The LinkedIn username to set.
   *
   * @return \Drupal\ohano_profile\Entity\SocialMediaProfile
   *   The active instance of this class.
   */
  public function setLinkedin(string $linkedin): SocialMediaProfile {
    $this->set('linkedin', $linkedin);
    return $this;
  }

  /**
   * Sets the Xing username.
   *
   * @param string $xing
   *   The Xing username to set.
   *
   * @return \Drupal\ohano_profile\Entity\SocialMediaProfile
   *   The active instance of this class.
   */
  public function setXing(string $xing): SocialMediaProfile {
    $this->set('xing', $xing);
    return $this;
  }

  /**
   * Sets the Pinterest username.
   *
   * @param string $pinterest
   *   The Pinterest username to set.
   *
   * @return \Drupal\ohano_profile\Entity\SocialMediaProfile
   *   The active instance of this class.
   */
  public function setPinterest(string $pinterest): SocialMediaProfile {
    $this->set('pinterest', $pinterest);
    return $this;
  }

  /**
   * Sets the Discord username.
   *
   * @param string $discord
   *   The Discord username to set.
   *
   * @return \Drupal\ohano_profile\Entity\SocialMediaProfile
   *   The active instance of this class.
   */
  public function setDiscord(string $discord): SocialMediaProfile {
    $this->set('discord', $discord);
    return $this;
  }

  /**
   * Sets the Behance username.
   *
   * @param string $behance
   *   The Behance username to set.
   *
   * @return \Drupal\ohano_profile\Entity\SocialMediaProfile
   *   The active instance of this class.
   */
  public function setBehance(string $behance): SocialMediaProfile {
    $this->set('behance', $behance);
    return $this;
  }

  /**
   * Sets the Dribbble username.
   *
   * @param string $dribbble
   *   The Dribbble username to set.
   *
   * @return \Drupal\ohano_profile\Entity\SocialMediaProfile
   *   The active instance of this class.
   */
  public function setDribbble(string $dribbble): SocialMediaProfile {
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
