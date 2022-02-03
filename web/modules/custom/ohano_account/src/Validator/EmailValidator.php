<?php

namespace Drupal\ohano_account\Validator;

use Drupal\Component\Utility\EmailValidator as DrupalEmailValidator;

class EmailValidator {

  const VALID = 1;
  const IN_USE = 2;
  const NOT_EMAIL = 4;

  public function validateEmail(string $email): int {
    // Validate email against RFC validation.
    $emailValidator = new DrupalEmailValidator();
    if (!$emailValidator->isValid($email)) {
      return self::NOT_EMAIL;
    }

    $userQuery = \Drupal::entityQuery('user');
    $condition = $userQuery->orConditionGroup();

    /** @var \Drupal\ohano_account\Filter\Email\GoogleMailFilter $gmailFilter */
    $gmailFilter = \Drupal::service('ohano_account.filter_email.googlemailfilter');
    if ($gmailFilter->isGoogleMailAddress($email)) {
      $variants = $gmailFilter->getVariants($email);
      foreach ($variants as $variant) {
        $condition->condition('mail', $variant);
      }
    } else {
      $condition->condition('mail', $email);
    }

    $userQuery->condition($condition);
    $result = $userQuery->execute();

    if (count($result)) {
      return self::IN_USE;
    }

    return self::VALID;
  }

}
