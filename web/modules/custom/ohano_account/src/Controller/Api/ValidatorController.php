<?php

namespace Drupal\ohano_account\Controller\Api;

use Drupal\Core\Controller\ControllerBase;
use Drupal\ohano_account\Validator\UsernameValidator;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Controller for all API actions about validations.
 *
 * @package Drupal\ohano_account\Controller\Api
 */
class ValidatorController extends ControllerBase {

  /**
   * Validates the username.
   *
   * @param string $username
   *   The username to validate.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   The JSON response.
   */
  public function validateUsername(string $username): JsonResponse {
    /** @var \Drupal\ohano_account\Validator\UsernameValidator $usernameValidator */
    $usernameValidator = \Drupal::service('ohano_account.validator.username');
    $validated = $usernameValidator->validateUsername($username);
    $code = 200;

    if ($validated != UsernameValidator::VALID) {
      $code = 400;
    }

    return new JsonResponse($validated, $code);
  }

}
