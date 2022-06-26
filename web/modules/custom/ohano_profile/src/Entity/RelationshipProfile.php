<?php

namespace Drupal\ohano_profile\Entity;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\ohano_profile\Option\RelationshipStatus;
use Drupal\ohano_profile\Option\RelationshipType;
use Drupal\ohano_profile\Option\Sexuality;
use http\Exception\InvalidArgumentException;

/**
 * Defines the RelationshipProfile entity.
 *
 * @package Drupal\ohano_profile\Entity
 *
 * @noinspection PhpUnused
 *
 * @ContentEntityType(
 *   id = "relationship_profile",
 *   label = @Translation("Relationship profile"),
 *   base_table = "ohano_relationship_profile",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *     "created" = "created",
 *     "updated" = "updated",
 *     "profile" = "profile",
 *     "relationship_status" = "relationship_status",
 *     "relationship_type" = "relationship_type",
 *     "sexuality" = "sexuality",
 *   },
 *   handlers = {
 *     "storage_schema" = "Drupal\ohano_profile\Storage\Schema\SubProfileStorageSchema",
 *   }
 * )
 */
class RelationshipProfile extends SubProfileBase {

  const ENTITY_ID = 'relationship_profile';

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

    $fields['relationship_status'] = BaseFieldDefinition::create('list_string')
      ->setSetting('allowed_values', RelationshipStatus::translatableFormOptions());
    $fields['relationship_type'] = BaseFieldDefinition::create('list_string')
      ->setSetting('allowed_values', RelationshipType::translatableFormOptions());
    $fields['sexuality'] = BaseFieldDefinition::create('list_string')
      ->setSetting('allowed_values', Sexuality::translatableFormOptions());

    return $fields;
  }

  /**
   * Gets the relationship status.
   *
   * @return \Drupal\ohano_profile\Option\RelationshipStatus|null
   *   The relationship status.
   */
  public function getRelationshipStatus(): ?RelationshipStatus {
    return RelationshipStatus::tryFrom($this->get('relationship_status')->value ?? "");
  }

  /**
   * Gets the relationship type.
   *
   * @return \Drupal\ohano_profile\Option\RelationshipType|null
   *   The relationship type.
   */
  public function getRelationshipType(): ?RelationshipType {
    return RelationshipType::tryFrom($this->get('relationship_type')->value ?? "");
  }

  /**
   * Gets the sexuality.
   *
   * @return \Drupal\ohano_profile\Option\Sexuality|null
   *   The sexuality.
   */
  public function getSexuality(): ?Sexuality {
    return Sexuality::tryFrom($this->get('sexuality')->value ?? "");
  }

  /**
   * Sets the relationship status.
   *
   * @param \Drupal\ohano_profile\Option\RelationshipStatus|null $relationshipStatus
   *   The relationship status to set.
   *
   * @return \Drupal\ohano_profile\Entity\RelationshipProfile
   *   The active instance of this class.
   */
  public function setRelationshipStatus(RelationshipStatus $relationshipStatus = NULL): RelationshipProfile {
    $this->set('relationship_status', $relationshipStatus->value);
    return $this;
  }

  /**
   * Sets the relationship type.
   *
   * @param \Drupal\ohano_profile\Option\RelationshipType|null $relationshipType
   *   The relationship type to set.
   *
   * @return \Drupal\ohano_profile\Entity\RelationshipProfile
   *   The active instance of this class.
   */
  public function setRelationshipType(RelationshipType $relationshipType = NULL): RelationshipProfile {
    $this->set('relationship_type', $relationshipType->value);
    return $this;
  }

  /**
   * Sets the sexuality.
   *
   * @param \Drupal\ohano_profile\Option\Sexuality|null $sexuality
   *   The sexuality to set.
   *
   * @return \Drupal\ohano_profile\Entity\RelationshipProfile
   *   The active instance of this class.
   *
   * @noinspection PhpUnused
   */
  public function setSexuality(Sexuality $sexuality = NULL): RelationshipProfile {
    $this->set('sexuality', $sexuality->value);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function render(): array {
    return parent::render() + [
      'relationship_status' => $this->getRelationshipStatus()?->value,
      'relationship_status_value' => $this->getRelationshipStatus() ? t($this->getRelationshipStatus()->value) : NULL,
      'relationship_type' => $this->getRelationshipType()?->value,
      'relationship_type_value' => $this->getRelationshipType() ? t($this->getRelationshipType()->value) : NULL,
      'sexuality' => $this->getSexuality()?->value,
      'sexuality_value' => $this->getSexuality() ? t($this->getSexuality()->value) : NULL,
    ];
  }

  public static function renderForm(SubProfileBase $subProfile): array {
    if (!$subProfile instanceof RelationshipProfile) {
      throw new InvalidArgumentException('Parameter must be of type RelationshipProfile');
    }
    /** @var \Drupal\ohano_profile\Entity\RelationshipProfile $subProfile */

    $form = [];

    $form['status'] = [
      '#type' => 'select',
      '#title' => t('Relationship status'),
      '#options' => [NULL => '-'] + RelationshipStatus::translatableFormOptions(),
      '#default_value' => $subProfile->getRelationshipStatus()?->value,
    ];

    $form['type'] = [
      '#type' => 'select',
      '#title' => t('Preferred relationship type'),
      '#options' => [NULL => '-'] + RelationshipType::translatableFormOptions(),
      '#default_value' => $subProfile->getRelationshipType()?->value,
    ];

    $form['sexuality'] = [
      '#type' => 'select',
      '#title' => t('Sexuality'),
      '#options' => [NULL => '-'] + Sexuality::translatableFormOptions(),
      '#default_value' => $subProfile->getSexuality()?->value,
    ];

    return $form;
  }

}
