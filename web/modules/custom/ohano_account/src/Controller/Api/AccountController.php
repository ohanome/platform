<?php

namespace Drupal\ohano_account\Controller\Api;

use Drupal\Core\Controller\ControllerBase;
use Drupal\ohano_account\Entity\Account;
use Drupal\ohano_account\Option\ColorMode;
use Drupal\ohano_account\Option\ColorShade;
use Drupal\ohano_account\Option\FontSize;
use Symfony\Component\HttpFoundation\JsonResponse;

class AccountController extends ControllerBase {

  public function setFontSize(string $fontSize) {
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

  public function setColorMode(string $colorMode) {
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

  public function setColorShade(string $colorShade) {
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
