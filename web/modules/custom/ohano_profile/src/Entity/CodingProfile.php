<?php

namespace Drupal\ohano_profile\Entity;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\taxonomy\Entity\Term;

/**
 * Defines the CodingProfile entity.
 *
 * @package Drupal\ohano_profile\Entity\Profile
 *
 * @noinspection PhpUnused
 *
 * @ContentEntityType(
 *   id = "coding_profile",
 *   label = @Translation("Coding profile"),
 *   base_table = "ohano_coding_profile",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *     "created" = "created",
 *     "updated" = "updated",
 *     "profile" = "profile",
 *   },
 *   handlers = {
 *     "storage_schema" = "Drupal\ohano_profile\Storage\Schema\SubProfileStorageSchema",
 *   }
 * )
 */
class CodingProfile extends SubProfileBase {

  const ENTITY_ID = 'coding_profile';

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

    $fields['github'] = BaseFieldDefinition::create('string');
    $fields['gitlab'] = BaseFieldDefinition::create('string');
    $fields['bitbucket'] = BaseFieldDefinition::create('string');
    $fields['codepen'] = BaseFieldDefinition::create('string');
    $fields['programming_languages'] = BaseFieldDefinition::create('entity_reference')
      ->setSetting('target_type', 'taxonomy_term')
      ->setSetting('handler', 'default')
      ->setSetting('handler_settings', [
        'target_bundles' => [
          'tags' => 'programming_languages',
        ],
      ])
      ->setCardinality(-1);
    $fields['systems'] = BaseFieldDefinition::create('entity_reference')
      ->setSetting('target_type', 'taxonomy_term')
      ->setSetting('handler', 'default')
      ->setSetting('handler_settings', [
        'target_bundles' => [
          'tags' => 'systems',
        ],
      ])
      ->setCardinality(-1);

    return $fields;
  }

  /**
   * Gets the GitHub username.
   *
   * @return string|null
   *   The GitHub username.
   */
  public function getGithub(): ?string {
    return $this->get('github')->value;
  }

  /**
   * Gets the GitLab username.
   *
   * @return string|null
   *   The GitLab username.
   */
  public function getGitlab(): ?string {
    return $this->get('gitlab')->value;
  }

  /**
   * Gets the BitBucket username.
   *
   * @return string|null
   *   The BitBucket username.
   */
  public function getBitbucket(): ?string {
    return $this->get('bitbucket')->value;
  }

  /**
   * Gets the codepen username.
   *
   * @return string|null
   *   The codepen username.
   */
  public function getCodepen(): ?string {
    return $this->get('codepen')->value;
  }

  /**
   * Sets the GitHub username.
   *
   * @param string $github
   *   The GitHub username to set.
   *
   * @return \Drupal\ohano_profile\Entity\CodingProfile
   *   The active instance of this class.
   */
  public function setGithub(string $github): CodingProfile {
    $this->set('github', $github);
    return $this;
  }

  /**
   * Sets the GitLab username.
   *
   * @param string $gitlab
   *   The GitLab username to set.
   *
   * @return \Drupal\ohano_profile\Entity\CodingProfile
   *   The active instance of this class.
   */
  public function setGitlab(string $gitlab): CodingProfile {
    $this->set('gitlab', $gitlab);
    return $this;
  }

  /**
   * Sets the BitBucket username.
   *
   * @param string $bitbucket
   *   The BitBucket username to set.
   *
   * @return \Drupal\ohano_profile\Entity\CodingProfile
   *   The active instance of this class.
   */
  public function setBitbucket(string $bitbucket): CodingProfile {
    $this->set('bitbucket', $bitbucket);
    return $this;
  }

  /**
   * Sets the codepen username.
   *
   * @param string $codepen
   *   The codepen username to set.
   *
   * @return \Drupal\ohano_profile\Entity\CodingProfile
   *   The active instance of this class.
   */
  public function setCodepen(string $codepen): CodingProfile {
    $this->set('codepen', $codepen);
    return $this;
  }

  /**
   * Gets the programming languages.
   *
   * @return \Drupal\taxonomy\Entity\Term[]|null
   *   The programming languages.
   */
  public function getProgrammingLanguages(): ?array {
    return $this->get('programming_languages')->referencedEntities();
  }

  /**
   * Gets the systems.
   *
   * @return \Drupal\taxonomy\Entity\Term[]|null
   *   The systems.
   */
  public function getSystems(): ?array {
    return $this->get('systems')->referencedEntities();
  }

  /**
   * Sets the programming languages.
   *
   * @param \Drupal\taxonomy\Entity\Term[]|array[]|null $programmingLanguages
   *   The programming languages to set.
   *
   * @return \Drupal\ohano_profile\Entity\CodingProfile
   *   The active instance of this class.
   *
   * @noinspection PhpUnused
   */
  public function setProgrammingLanguages(array $programmingLanguages = NULL): CodingProfile {
    $this->setTermValues('programming_languages', $programmingLanguages);
    return $this;
  }

  /**
   * Sets the systems.
   *
   * @param \Drupal\taxonomy\Entity\Term[]|array[]|null $systems
   *   The systems to set.
   *
   * @return \Drupal\ohano_profile\Entity\CodingProfile
   *   The active instance of this class.
   *
   * @noinspection PhpUnused
   */
  public function setSystems(array $systems = NULL): CodingProfile {
    $this->setTermValues('systems', $systems);
    return $this;
  }

  /**
   * Adds the given programming language to the list of games.
   *
   * @param \Drupal\taxonomy\Entity\Term $programmingLanguage
   *   The programming language to add.
   *
   * @return \Drupal\ohano_profile\Entity\CodingProfile
   *   The active instance of this class.
   *
   * @noinspection PhpUnused
   */
  public function addProgrammingLanguage(Term $programmingLanguage): CodingProfile {
    foreach ($this->getProgrammingLanguages() as $savedProgrammingLanguage) {
      if ($programmingLanguage->id() == $savedProgrammingLanguage->id()) {
        return $this;
      }
    }

    $this->get('programming_languages')->appendItem($programmingLanguage);
    return $this;
  }

  /**
   * Removes the given programming language from the list of games.
   *
   * @param \Drupal\taxonomy\Entity\Term $programmingLanguage
   *   The programming language to remove.
   *
   * @return \Drupal\ohano_profile\Entity\CodingProfile
   *   The active instance of this class.
   *
   * @noinspection PhpUnused
   */
  public function removeProgrammingLanguage(Term $programmingLanguage): CodingProfile {
    // Find the index.
    $foundIndex = NULL;
    foreach ($this->getProgrammingLanguages() as $index => $term) {
      if ($term->id() == $programmingLanguage->id()) {
        $foundIndex = $index;
      }
    }

    if ($foundIndex != NULL) {
      $this->get('programming_languages')->removeItem($foundIndex);
    }

    return $this;
  }

  /**
   * Adds the given  system to the list of  systems.
   *
   * @param \Drupal\taxonomy\Entity\Term $system
   *   The system to add.
   *
   * @return \Drupal\ohano_profile\Entity\CodingProfile
   *   The active instance of this class.
   *
   * @noinspection PhpUnused
   */
  public function addSystem(Term $system): CodingProfile {
    foreach ($this->getSystems() as $savedSystem) {
      if ($system->id() == $savedSystem->id()) {
        return $this;
      }
    }

    $this->get('systems')->appendItem($system);
    return $this;
  }

  /**
   * Removes the given system from the list of systems.
   *
   * @param \Drupal\taxonomy\Entity\Term $system
   *   The system to remove.
   *
   * @return \Drupal\ohano_profile\Entity\CodingProfile
   *   The active instance of this class.
   *
   * @noinspection PhpUnused
   */
  public function removeSystem(Term $system): CodingProfile {
    // Find the index.
    $foundIndex = NULL;
    foreach ($this->getSystems() as $index => $term) {
      if ($term->id() == $system->id()) {
        $foundIndex = $index;
      }
    }

    if ($foundIndex != NULL) {
      $this->get('systems')->removeItem($foundIndex);
    }

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function render(): array {
    /** @var \Drupal\ohano_core\Service\RenderService $renderService */
    $renderService = \Drupal::service('ohano_core.render');

    $programmingLanguages = $this->getProgrammingLanguages();
    $renderedProgrammingLanguages = [];
    if (!empty($programmingLanguages)) {
      foreach ($programmingLanguages as $programmingLanguage) {
        $renderedProgrammingLanguages[] = $renderService->renderTerm($programmingLanguage);
      }
    }

    $systems = $this->getSystems();
    $renderedSystems = [];
    if (!empty($systems)) {
      foreach ($systems as $system) {
        $renderedSystems[] = $renderService->renderTerm($system);
      }
    }

    return parent::render() + [
      'github' => $this->getGithub(),
      'gitlab' => $this->getGitlab(),
      'bitbucket' => $this->getBitbucket(),
      'codepen' => $this->getCodepen(),
      'programming_languages' => $renderedProgrammingLanguages,
      'systems' => $renderedSystems,
    ];
  }

  /**
   * Renders the coding profile form.
   *
   * @param \Drupal\ohano_profile\Entity\SubProfileBase $subProfile
   *   The sub profile to render to form for.
   *
   * @return array
   *   The render array.
   *
   * @throws \Exception
   */
  public static function renderForm(SubProfileBase $subProfile): array {
    if (!$subProfile instanceof CodingProfile) {
      throw new \Exception('Parameter must be of type CodingProfile');
    }
    /** @var \Drupal\ohano_profile\Entity\CodingProfile $subProfile */

    $form = [];

    $form['github'] = [
      '#type' => 'textfield',
      '#title' => t('GitHub'),
      '#default_value' => $subProfile->getGithub(),
    ];

    $form['gitlab'] = [
      '#type' => 'textfield',
      '#title' => t('GitLab'),
      '#default_value' => $subProfile->getGitlab(),
    ];

    $form['bitbucket'] = [
      '#type' => 'textfield',
      '#title' => t('BitBucket'),
      '#default_value' => $subProfile->getBitbucket(),
    ];

    $allTerms = \Drupal::entityQuery('taxonomy_term')
      ->condition('vid', 'programming_languages')
      ->execute();
    $allTerms = Term::loadMultiple($allTerms);
    foreach ($allTerms as $tid => $term) {
      $allTerms[$tid] = $term->getName();
    }

    $selectedTerms = $subProfile->getProgrammingLanguages();
    foreach ($selectedTerms as $index => $term) {
      $selectedTerms[$index] = $term->id();
    }

    $form['programming_languages'] = [
      '#type' => 'select',
      '#multiple' => TRUE,
      '#title' => t('Programming languages'),
      '#options' => $allTerms,
      '#default_value' => $selectedTerms,
      '#chosen' => TRUE,
    ];

    $allTerms = \Drupal::entityQuery('taxonomy_term')
      ->condition('vid', 'systems')
      ->execute();
    $allTerms = Term::loadMultiple($allTerms);
    foreach ($allTerms as $tid => $term) {
      $allTerms[$tid] = $term->getName();
    }

    $selectedTerms = $subProfile->getSystems();
    foreach ($selectedTerms as $index => $term) {
      $selectedTerms[$index] = $term->id();
    }

    $form['systems'] = [
      '#type' => 'select',
      '#multiple' => TRUE,
      '#title' => t('Systems'),
      '#description' => t('Frameworks, *MS (CMS, DMS, ...), etc.'),
      '#options' => $allTerms,
      '#default_value' => $selectedTerms,
      '#chosen' => TRUE,
    ];

    return $form;
  }

}
