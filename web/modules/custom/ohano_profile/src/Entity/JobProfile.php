<?php

namespace Drupal\ohano_profile\Entity;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\ohano_profile\Option\EducationDegree;
use Drupal\ohano_profile\Option\EmploymentStatus;

/**
 * Defines the JobProfile entity.
 *
 * @package Drupal\ohano_profile\Entity
 *
 * @noinspection PhpUnused
 *
 * @ContentEntityType(
 *   id = "job_profile",
 *   label = @Translation("Job profile"),
 *   base_table = "ohano_job_profile",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *     "created" = "created",
 *     "updated" = "updated",
 *     "profile" = "profile",
 *     "employment_status" = "employment_status",
 *     "education_degree" ="education_degree",
 *     "employer" = "employer",
 *     "industry" = "industry",
 *     "position" = "position",
 *   },
 *   handlers = {
 *     "storage_schema" = "Drupal\ohano_profile\Storage\Schema\SubProfileStorageSchema",
 *   }
 * )
 */
class JobProfile extends SubProfileBase {

  const ENTITY_ID = 'job_profile';

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

    $fields['employment_status'] = BaseFieldDefinition::create('list_string')
      ->setSetting('allowed_values', EmploymentStatus::translatableFormOptions());
    $fields['education_degree'] = BaseFieldDefinition::create('list_string')
      ->setSetting('allowed_values', EducationDegree::translatableFormOptions());
    $fields['employer'] = BaseFieldDefinition::create('string');
    $fields['industry'] = BaseFieldDefinition::create('string');
    $fields['position'] = BaseFieldDefinition::create('string');

    return $fields;
  }

  /**
   * Gets the employment status.
   *
   * @return \Drupal\ohano_profile\Option\EmploymentStatus|null
   *   The employment status.
   */
  public function getEmploymentStatus(): ?EmploymentStatus {
    return EmploymentStatus::tryFrom($this->get('employment_status')->value);
  }

  /**
   * Gets the education degree.
   *
   * @return \Drupal\ohano_profile\Option\EducationDegree|null
   *   The education degree.
   */
  public function getEducationDegree(): ?EducationDegree {
    return EducationDegree::tryFrom($this->get('education_degree')->value);
  }

  /**
   * Gets the employer.
   *
   * @return string|null
   *   The employer.
   */
  public function getEmployer(): ?string {
    return $this->get('employer')->value;
  }

  /**
   * Gets the industry.
   *
   * @return string|null
   *   The industry.
   */
  public function getIndustry(): ?string {
    return $this->get('industry')->value;
  }

  /**
   * Gets the position.
   *
   * @return string|null
   *   The position.
   */
  public function getPosition(): ?string {
    return $this->get('position')->value;
  }

  /**
   * Sets the employment status.
   *
   * @param \Drupal\ohano_profile\Option\EmploymentStatus $employmentStatus
   *   The employment status to set.
   *
   * @return \Drupal\ohano_profile\Entity\JobProfile
   *   The active instance of this class.
   *
   * @noinspection PhpUnused
   */
  public function setEmploymentStatus(EmploymentStatus $employmentStatus): JobProfile {
    $this->set('employment_status', $employmentStatus->value);
    return $this;
  }

  /**
   * Sets the education degree.
   *
   * @param \Drupal\ohano_profile\Option\EducationDegree $educationDegree
   *   The education degree to set.
   *
   * @return \Drupal\ohano_profile\Entity\JobProfile
   *   The active instance of this class.
   *
   * @noinspection PhpUnused
   */
  public function setEducationDegree(EducationDegree $educationDegree): JobProfile {
    $this->set('education_degree', $educationDegree->value);
    return $this;
  }

  /**
   * Sets the employer.
   *
   * @param string $employer
   *   The employer to set.
   *
   * @return \Drupal\ohano_profile\Entity\JobProfile
   *   The active instance of this class.
   */
  public function setEmployer(string $employer): JobProfile {
    $this->set('employer', $employer);
    return $this;
  }

  /**
   * Sets the industry.
   *
   * @param string $industry
   *   The industry to set.
   *
   * @return \Drupal\ohano_profile\Entity\JobProfile
   *   The active instance of this class.
   */
  public function setIndustry(string $industry): JobProfile {
    $this->set('industry', $industry);
    return $this;
  }

  /**
   * Sets the position.
   *
   * @param string $position
   *   The position to set.
   *
   * @return \Drupal\ohano_profile\Entity\JobProfile
   *   The active instance of this class.
   *
   * @noinspection PhpUnused
   */
  public function setPosition(string $position): JobProfile {
    $this->set('position', $position);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function render(): array {
    return parent::render() + [
      'employment_status' => $this->getEmploymentStatus(),
      'education_degree' => $this->getEducationDegree(),
      'employer' => $this->getEmployer(),
      'industry' => $this->getIndustry(),
      'position' => $this->getPosition(),
    ];
  }

}
