<?php

namespace Drupal\ohano_account\Controller\Api;

use Drupal\Core\Controller\ControllerBase;
use Drupal\ohano_account\Entity\Account;
use Drupal\ohano_account\Option\ColorMode;
use Drupal\ohano_account\Option\ColorShade;
use Drupal\ohano_account\Option\FontSize;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Controller for all API actions about accounts.
 *
 * @package Drupal\ohano_account\Controller\Api
 */
class AccountController extends ControllerBase {

  /**
   * Sets the font size for the currently logged-in user.
   *
   * @param string $fontSize
   *   The font size to set.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   The JSON response.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function setFontSize(string $fontSize): JsonResponse {
    $originalValue = $fontSize;
    $fontSize = FontSize::tryFrom(strtoupper($fontSize))?->value ?? FontSize::M->value;
    $account = Account::forActive();
    if (empty($account)) {
      return new JsonResponse("NULL:$originalValue:$fontSize:ACCOUNT_EMPTY");
    }
    $name = $account->getUser()->getAccountName();

    $account->set('font_size', $fontSize)
      ->save();

    return new JsonResponse("$name:$originalValue:$fontSize:OK");
  }

  /**
   * Sets the color mode for the currently logged-in user.
   *
   * @param string $colorMode
   *   The color mode to set.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   The JSON response.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function setColorMode(string $colorMode): JsonResponse {
    $originalValue = $colorMode;
    $colorMode = ColorMode::tryFrom(ucfirst($colorMode))?->value ?? ColorMode::Light->value;
    $account = Account::forActive();
    if (empty($account)) {
      return new JsonResponse("NULL:$originalValue:$colorMode:ACCOUNT_EMPTY");
    }
    $name = $account->getUser()->getAccountName();
    $account->set('color_mode', $colorMode)
      ->save();

    return new JsonResponse("$name:$originalValue:$colorMode:OK");
  }

  /**
   * Sets the color shade for the currently logged-in user.
   *
   * @param string $colorShade
   *   The color shade to set.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   The JSON response.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function setColorShade(string $colorShade): JsonResponse {
    $originalValue = $colorShade;
    $colorShade = ColorShade::tryFrom(ucfirst($colorShade))?->value ?? ColorShade::Neutral->value;
    $account = Account::forActive();
    if (empty($account)) {
      return new JsonResponse("NULL:$originalValue:$colorShade:ACCOUNT_EMPTY");
    }
    $name = $account->getUser()->getAccountName();
    $account->set('color_shade', $colorShade)
      ->save();

    return new JsonResponse("$name:$originalValue:$colorShade:OK");
  }

}
