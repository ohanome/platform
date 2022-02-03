<?php

namespace Drupal\ohano_account\Filter\Email;

class GoogleMailFilter {

  public function isGoogleMailAddress(string $email): bool {
    if (preg_match_all('/g(oogle|)mail\.com/', $email)) {
      return TRUE;
    }

    return FALSE;
  }

  public function getVariants(string $email): array {
    $emailName = preg_replace('/@g(oogle|)mail\.com/', '', $email);
    return [
      "$emailName@googlemail.com",
      "$emailName@gmail.com",
    ];
  }

}
