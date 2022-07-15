<?php

namespace Drupal\ohano_profile\Entity;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\ohano_core\Entity\EntityBase;
use Drupal\taxonomy\TermInterface;

/**
 * Defines the SkillBase entity base class for use in skill fields.
 *
 * @package Drupal\ohano_profile\Entity
 */
abstract class SkillBase extends EntityBase {

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['skill'] = BaseFieldDefinition::create('entity_reference')
      ->setSetting('target_type', 'taxonomy_term')
      ->setSetting('handler', 'default')
      ->setCardinality(1);

    $fields['projects'] = BaseFieldDefinition::create('integer')
      ->setSetting('unsigned', TRUE)
      ->setSetting('min', 0)
      ->setDefaultValue(0);

    $fields['experience_time'] = BaseFieldDefinition::create('datetime')
      ->setSetting('datetime_type', 'date')
      ->setSetting('datetime_format', 'Y-m-d')
      ->setDefaultValue((new DrupalDateTime())->format('Y-m-d'));

    return $fields;
  }

  /**
   * Get the skill.
   *
   * @return \Drupal\taxonomy\TermInterface
   *   The skill.
   */
  public function getSkill(): TermInterface {
    return $this->get('skill')->entity;
  }

  /**
   * Get the projects.
   *
   * @return int
   *   The projects.
   */
  public function getProjects(): int {
    return $this->get('projects')->value;
  }

  /**
   * Get the experience time.
   *
   * @return \Drupal\Core\Datetime\DrupalDateTime
   *   The experience time.
   */
  public function getExperienceTime(): DrupalDateTime {
    return $this->get('experience_time')->date;
  }

  /**
   * Set the skill.
   *
   * @param \Drupal\taxonomy\TermInterface $skill
   *   The skill.
   *
   * @return \Drupal\ohano_profile\Entity\SkillBase
   *   The entity.
   */
  public function setSkill(TermInterface $skill): SkillBase {
    $this->set('skill', $skill);
    return $this;
  }

  /**
   * Set the projects.
   *
   * @param int $projects
   *   The projects.
   *
   * @return \Drupal\ohano_profile\Entity\SkillBase
   *   The entity.
   */
  public function setProjects(int $projects): SkillBase {
    $this->set('projects', $projects);
    return $this;
  }

  /**
   * Set the experience time.
   *
   * @param \Drupal\Core\Datetime\DrupalDateTime $experience_time
   *   The experience time.
   *
   * @return \Drupal\ohano_profile\Entity\SkillBase
   *   The entity.
   */
  public function setExperienceTime(DrupalDateTime $experience_time): SkillBase {
    $this->set('experience_time', $experience_time->format('Y-m-d'));
    return $this;
  }

}
