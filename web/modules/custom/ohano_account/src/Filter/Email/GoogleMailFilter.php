<?php

namespace Drupal\ohano_account\Filter\Email;

/**
 * Filter that finds Google Mail addresses.
 *
 * @package Drupal\ohano_account\Filter\Email
 */
class GoogleMailFilter {

  /**
   * Checks if the given email address is a Google Mail address.
   *
   * @param string $email
   *   The email address to check.
   *
   * @return bool
   *   TRUE if the mail address is from Google, FALSE if not.
   */
  public function isGoogleMailAddress(string $email): bool {
    if (preg_match_all('/g(oogle|)mail\.com/', $email)) {
      return TRUE;
    }

    return FALSE;
  }

  /**
   * Returns all possible variants of the email address.
   *
   * @param string $email
   *   The email address to use as base.
   *
   * @return string[]
   *   An array containing all possible Google Mail variants.
   */
  public function getVariants(string $email): array {
    $emailName = preg_replace('/@g(oogle|)mail\.com/', '', $email);
    return [
      "$emailName@googlemail.com",
      "$emailName@gmail.com",
    ];
  }

}
