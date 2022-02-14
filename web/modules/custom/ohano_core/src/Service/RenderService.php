<?php

namespace Drupal\ohano_core\Service;

use Drupal\Core\Session\AccountInterface;
use Drupal\file\Entity\File;
use Drupal\taxonomy\Entity\Term;

/**
 * Service class to provide render methods for non-ohano entities.
 *
 * Entities which are non-ohano are for example Nodes, Terms, Media or Files.
 *
 * @package Drupal\ohano_core\Service
 */
class RenderService {

  /**
   * Renders a given term.
   *
   * The returned array will include the key "fields" which will hold all extra
   * added fields. Such fields are added via the admin panel.
   *
   * @param \Drupal\taxonomy\Entity\Term $term
   *   The term entity to render.
   *
   * @return array
   *   The rendered term.
   *
   * @noinspection PhpUnused
   */
  public function renderTerm(Term $term): array {
    $build = [];

    $build['id'] = $term->id();
    $build['name'] = $term->getName();
    $build['fields'] = $term->getFields(FALSE);

    return $build;
  }

  /**
   * Renders the given user.
   *
   * @param \Drupal\Core\Session\AccountInterface $user
   *   The user to render.
   *
   * @return array
   *   The rendered user.
   */
  public function renderUser(AccountInterface $user): array {
    $build = [];

    $build['uid'] = $user->id();
    $build['username'] = $user->getAccountName();

    return $build;
  }

  /**
   * Renders the given file entity.
   *
   * @param \Drupal\file\Entity\File $file
   *   The file to render.
   *
   * @return array
   *   The rendered file.
   */
  public function renderFile(File $file): array {
    $build = [];

    $build['filename'] = $file->getFilename();
    $build['url'] = $file->createFileUrl();
    $build['mime'] = $file->getMimeType();

    return $build;
  }

}
