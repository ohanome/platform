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
   * @param \Drupal\ohano_profile\Option\EmploymentStatus|null $employmentStatus
   *   The employment status to set.
   *
   * @return \Drupal\ohano_profile\Entity\JobProfile
   *   The active instance of this class.
   *
   * @noinspection PhpUnused
   */
  public function setEmploymentStatus(EmploymentStatus $employmentStatus = NULL): JobProfile {
    $this->set('employment_status', $employmentStatus?->value);
    return $this;
  }

  /**
   * Sets the education degree.
   *
   * @param \Drupal\ohano_profile\Option\EducationDegree|null $educationDegree
   *   The education degree to set.
   *
   * @return \Drupal\ohano_profile\Entity\JobProfile
   *   The active instance of this class.
   *
   * @noinspection PhpUnused
   */
  public function setEducationDegree(EducationDegree $educationDegree = NULL): JobProfile {
    $this->set('education_degree', $educationDegree?->value);
    return $this;
  }

  /**
   * Sets the employer.
   *
   * @param string|null $employer
   *   The employer to set.
   *
   * @return \Drupal\ohano_profile\Entity\JobProfile
   *   The active instance of this class.
   */
  public function setEmployer(string $employer = NULL): JobProfile {
    $this->set('employer', $employer);
    return $this;
  }

  /**
   * Sets the industry.
   *
   * @param string|null $industry
   *   The industry to set.
   *
   * @return \Drupal\ohano_profile\Entity\JobProfile
   *   The active instance of this class.
   */
  public function setIndustry(string $industry = NULL): JobProfile {
    $this->set('industry', $industry);
    return $this;
  }

  /**
   * Sets the position.
   *
   * @param string|null $position
   *   The position to set.
   *
   * @return \Drupal\ohano_profile\Entity\JobProfile
   *   The active instance of this class.
   *
   * @noinspection PhpUnused
   */
  public function setPosition(string $position = NULL): JobProfile {
    $this->set('position', $position);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function render(): array {
    return parent::render() + [
      'employment_status' => $this->getEmploymentStatus()?->value,
        // phpcs:ignore
      'employment_status_value' => $this->getEmploymentStatus() ? t($this->getEmploymentStatus()->value) : NULL,
      'education_degree' => $this->getEducationDegree()?->value,
        // phpcs:ignore
      'education_degree_value' => $this->getEducationDegree() ? t($this->getEducationDegree()->value) : NULL,
      'employer' => $this->getEmployer(),
      'industry' => $this->getIndustry(),
      'position' => $this->getPosition(),
    ];
  }

  /**
   * Renders the job profile form.
   *
   * @param \Drupal\ohano_profile\Entity\SubProfileBase $subProfile
   *   The sub profile to render the form for.
   *
   * @return array
   *   The form array.
   *
   * @throws \Exception
   */
  public static function renderForm(SubProfileBase $subProfile): array {
    if (!$subProfile instanceof JobProfile) {
      throw new \Exception('Parameter must be of type JobProfile');
    }
    /** @var \Drupal\ohano_profile\Entity\JobProfile $subProfile */

    $form = [];

    $form['employment_status'] = [
      '#type' => 'select',
      '#title' => t('Employment status'),
      '#options' => [NULL => '-'] + EmploymentStatus::translatableFormOptions(),
      '#default_value' => $subProfile->getEmploymentStatus()->value,
    ];

    $form['education_degree'] = [
      '#type' => 'select',
      '#title' => t('Education degree'),
      '#options' => [NULL => '-'] + EducationDegree::translatableFormOptions(),
      '#default_value' => $subProfile->getEducationDegree()->value,
    ];

    $form['employer'] = [
      '#type' => 'textfield',
      '#title' => t('Employer'),
      '#default_value' => $subProfile->getEmployer(),
    ];

    $form['industry'] = [
      '#type' => 'textfield',
      '#title' => t('Industry'),
      '#default_value' => $subProfile->getIndustry(),
    ];

    $form['position'] = [
      '#type' => 'textfield',
      '#title' => t('Position'),
      '#default_value' => $subProfile->getPosition(),
    ];

    return $form;
  }

}
