<?php

namespace Drupal\ohano_core;

class Settings {

  /**
   * How long are activations valid?
   */
  const ACTIVATION_TTL = 60 * 60 * 24 * 7;

  const ACTIVATION_INVALIDATION_CODE = 'invalid';

  const STARTING_BITS = 10000;

}
