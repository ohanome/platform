<?php

namespace Drupal\ohano_account\Validator;

use Drupal\ohano_account\Blocklist;
use Drupal\ohano_profile\Entity\UserProfile;

/**
 * Validator class validating usernames.
 *
 * @package Drupal\ohano_account\Validator
 */
class UsernameValidator {

  const VALID = 1;
  const IN_USE = 2;
  const ABUSIVE = 4;

  const SIMILARITY_PERCENTAGE_LIMIT = 50.0;

  /**
   * Checks if the given username is in the blocklist.
   *
   * @param string $username
   *   The username to check.
   *
   * @return bool
   *   TRUE if the username is in the blocklist, FALSE otherwise.
   */
  public function isInBlockList(string $username): bool {
    if (in_array(strtolower($username), Blocklist::USERNAME)) {
      return TRUE;
    }

    foreach (Blocklist::USERNAME as $blockedUsername) {
      $perc = NULL;
      similar_text($username, $blockedUsername, $perc);

      if ($perc >= self::SIMILARITY_PERCENTAGE_LIMIT) {
        return TRUE;
      }
    }

    return FALSE;
  }

  /**
   * Validates the given email address.
   *
   * @param string $username
   *   The email address to check.
   *
   * @return int
   *   One of the class constants.
   */
  public function validateUsername(string $username): int {
    if ($this->isInBlockList($username)) {
      return self::ABUSIVE;
    }

    $profileQuery = \Drupal::entityQuery(UserProfile::entityTypeId())
      ->condition('profile_name', strtolower($username), 'LIKE')
      ->execute();

    if (count($profileQuery) > 0) {
      return self::IN_USE;
    }

    return self::VALID;
  }

}
