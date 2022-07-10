<?php

namespace Drupal\ohano_search\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\ohano_account\Entity\Account;
use Drupal\ohano_profile\Entity\UserProfile;
use Drupal\ohano_search\Form\SearchForm;

/**
 * Provides a controller for the search page.
 */
class SearchController extends ControllerBase {

  /**
   * Handles the search page.
   *
   * @return array
   *   A render array for the search page.
   */
  public function search(): array {
    $results = [];

    $query = \Drupal::request()->query->get('q');
    if (!empty($query)) {
      $profiles = \Drupal::entityQuery(UserProfile::entityTypeId())
        ->condition('profile_name', "%$query%", 'LIKE')
        ->execute();

      foreach ($profiles as $profileId => $profile) {
        /** @var \Drupal\ohano_profile\Entity\UserProfile $profileEntity */
        $profileEntity = UserProfile::load($profile);
        $score = NULL;

        if (Account::isInDeveloperMode()) {
          similar_text($query, $profileEntity->getProfileName(), $score);
          $score = round($score, 2);
        }

        $results[] = [
          '#theme' => 'search_result_entry',
          '#title' => $profileEntity->getProfileName(),
          '#score' => $score,
          '#link' => '/user/' . $profileEntity->getProfileName(),
        ];
      }

      if (!empty($results)) {
        usort($results, function ($a, $b) {
          return $b['#score'] <=> $a['#score'];
        });
      }
    }

    return [
      '#theme' => 'search_page',
      '#search_form' => \Drupal::formBuilder()->getForm(SearchForm::class),
      '#results' => $results,
      '#result_count' => count($results),
      '#cache' => [
        'max-age' => '0',
      ],
    ];
  }

}
