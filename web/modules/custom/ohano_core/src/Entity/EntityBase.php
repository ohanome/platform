<?php

namespace Drupal\ohano_core\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\ohano_core\OhanoCore;

/**
 * Base class for every entity.
 *
 * @package Drupal\ohano_core\Entity
 */
abstract class EntityBase extends ContentEntityBase implements EntityInterface {

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array {
    $fields = [];
    OhanoCore::createEntityDefaultFields($fields);

    return $fields;
  }

  /**
   * Gets the entity id.
   *
   * @return int
   *   The entity id.
   */
  public function getId(): int {
    return $this->id();
  }

  /**
   * Gets the entity uuid.
   *
   * @return string
   *   The entity uuid.
   */
  public function getUuid(): string {
    return $this->uuid();
  }

  /**
   * Gets the created timestamp of the entity.
   *
   * @return int
   *   The created timestamp.
   */
  public function getCreated(): int {
    return $this->get('created')->value;
  }

  /**
   * Gets the created timestamp as DateTime object.
   *
   * @return \DateTime
   *   The created timestamp as DateTime object.
   *
   * @noinspection PhpUnused
   */
  public function getCreatedDateTime(): \DateTime {
    return \DateTime::createFromFormat('U', $this->getCreated());
  }

  /**
   * Gets the updated timestamp of the entity.
   *
   * @return int
   *   The updated timestamp.
   */
  public function getUpdated(): int {
    return $this->get('updated')->value;
  }

  /**
   * Gets the updated timestamp as DateTime object.
   *
   * @return \DateTime
   *   The updated timestamp as DateTime object.
   *
   * @noinspection PhpUnused
   */
  public function getUpdatedDateTime(): \DateTime {
    return \DateTime::createFromFormat('U', $this->getUpdated());
  }

  /**
   * {@inheritdoc}
   *
   * @noinspection PhpArrayShapeAttributeCanBeAddedInspection
   */
  public function render(): array {
    return [
      'id' => $this->id(),
      'uuid' => $this->uuid(),
      'created' => $this->getCreated(),
      'updated' => $this->getUpdated(),
    ];
  }

}
