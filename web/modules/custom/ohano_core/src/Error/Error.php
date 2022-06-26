<?php

namespace Drupal\ohano_core\Error;

enum Error: string {
  case UserProfileNotFound = "P0404";
  case BaseProfileNotFound = "P1404";
  case SubProfileNotFound = "P2404";
}
