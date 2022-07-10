<?php

namespace Drupal\ohano_core;

/**
 * Static settings which only can be changed in this file.
 *
 * @package Drupal\ohano_core
 */
class Settings {

  /**
   * How long are activations valid?
   *
   * Initial value: 7 days.
   *
   * @var int
   */
  const ACTIVATION_TTL = 60 * 60 * 24 * 7;

  /**
   * Default invalidation code.
   *
   * @var string
   */
  const ACTIVATION_INVALIDATION_CODE = 'invalid';

  /**
   * Amount of bits each user gets at registration.
   *
   * @var int
   */
  const STARTING_BITS = 10000;

}
