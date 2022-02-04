<?php

namespace Drupal\ohano_mail;

enum OhanoMail: string {
  case AccountActivation = 'account-activation';
  case AccountVerificationAccepted = 'account-verification-accepted';
  case AccountVerificationUpdated = 'account-verification-updated';
}
