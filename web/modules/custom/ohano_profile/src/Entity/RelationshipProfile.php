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
    return RelationshipStatus::tryFrom($this->get('relationship_status')->value);
  }

  /**
   * Gets the relationship type.
   *
   * @return \Drupal\ohano_profile\Option\RelationshipType|null
   *   The relationship type.
   */
  public function getRelationshipType(): ?RelationshipType {
    return RelationshipType::tryFrom($this->get('relationship_type')->value);
  }

  /**
   * Gets the sexuality.
   *
   * @return \Drupal\ohano_profile\Option\Sexuality|null
   *   The sexuality.
   */
  public function getSexuality(): ?Sexuality {
    return Sexuality::tryFrom($this->get('sexuality')->value);
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
      'relationship_status' => $this->getRelationshipStatus(),
      'relationship_type' => $this->getRelationshipType(),
      'sexuality' => $this->getSexuality(),
    ];
  }

}
