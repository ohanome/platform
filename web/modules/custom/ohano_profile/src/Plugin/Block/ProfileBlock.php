<?php

namespace Drupal\ohano_profile\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\ohano_account\Entity\Account;
use Drupal\ohano_profile\Entity\BaseProfile;
use Drupal\ohano_profile\Entity\UserProfile;

/**
 * Provides a block showing the online status of the configured servers.
 *
 * @Block(
 *   id = "ohano_profile__profile_block",
 *   admin_label = @Translation("Profile Block"),
 *   category = @Translation("ohano Profile"),
 * )
 */
class ProfileBlock extends BlockBase {

  public function build() {
    $account = Account::forActive();
    $profile = $account->getActiveProfile();

    /** @var BaseProfile $baseProfile */
    $baseProfile = BaseProfile::loadByProfile($profile);
    $renderedBaseProfile = $baseProfile->render();
    $profilePicture = NULL;
    if ($baseProfile->getProfilePicture()) {
      $profilePicture = [
        '#theme' => 'profile_picture',
        '#image_url' => $renderedBaseProfile['profile_picture_url'],
        '#username' => $baseProfile->getUsername(),
      ];
    }

    $profiles = UserProfile::loadMultipleByUser(\Drupal::currentUser());
    $otherProfiles = [];
    foreach ($profiles as $otherProfile) {
      if ($otherProfile->getProfileName() != $profile->getProfileName()) {
        $otherProfiles[] = $otherProfile->getProfileName();
      }
    }
    if (empty($otherProfiles)) {
      $otherProfiles = NULL;
    }

    return [
      '#theme' => 'profile_block',
      '#profile_picture' => $profilePicture,
      '#other_profiles' => $otherProfiles,
      '#profile_name' => $profile->getProfileName(),
      '#cache' => [
        'max-age' => 0,
      ]
    ];
  }

}
