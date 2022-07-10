<?php

namespace Drupal\ohano_profile\Entity;

use Drupal\Core\Entity\EntityTypeInterface;

/**
 * Defines the ProgrammingLanguageSkill entity.
 *
 * @package Drupal\ohano_profile\Entity
 *
 * @ContentEntityType(
 *   id = "system_skill",
 *   label = @Translation("System Skill"),
 *   base_table = "ohano_skill_system",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *     "created" = "created",
 *     "updated" = "updated",
 *     "skill" = "skill",
 *     "projects" = "projects",
 *     "experience_time" = "experience_time",
 *   }
 * )
 */
class SystemSkill extends SkillBase {

  const ENTITY_TYPE = 'programming_language_skill';

  /**
   * {@inheritdoc}
   */
  public static function entityTypeId(): string {
    return self::ENTITY_TYPE;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['skill']->setSetting('handler_settings', [
      'target_bundles' => [
        'tags' => 'systems',
      ],
    ]);

    return $fields;
  }

}
