<?php

// phpcs:ignoreFile

namespace Drupal\ohano_mail;

/**
 * Enumeration holding all mails sent in context of ohano.
 *
 * @package Drupal\ohano_mail
 *
 * @todo: Remove 'phpcs:ignoreFile' once phpcs supports enums.
 */
enum OhanoMail: string {
  case AccountActivation = 'account-activation';
  case AccountVerificationAccepted = 'account-verification-accepted';
  case AccountVerificationUpdated = 'account-verification-updated';
}
