<?php

namespace Drupal\ohano_profile\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityStorageException;
use Drupal\ohano_account\Entity\Account;
use Drupal\ohano_profile\Entity\BaseProfile;
use Drupal\ohano_profile\Entity\CodingProfile;
use Drupal\ohano_profile\Entity\GamingProfile;
use Drupal\ohano_profile\Entity\JobProfile;
use Drupal\ohano_profile\Entity\RelationshipProfile;
use Drupal\ohano_profile\Entity\SocialMediaProfile;
use Drupal\ohano_profile\Entity\UserProfile;
use Drupal\ohano_profile\Form\SwitchActiveProfileForm;
use Drupal\ohano_profile\Option\ProfileType;
use Drupal\ohano_profile\Settings;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Provides a controller for everything about profiles.
 *
 * @package Drupal\ohano_profile\Controller
 */
class ProfileController extends ControllerBase {

  /**
   * Creates a new profile for the current user.
   *
   * @return \Symfony\Component\HttpFoundation\RedirectResponse
   *   A redirect response to the front page.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function createProfileBase(): RedirectResponse {
    $destination = NULL;
    if (!empty(\Drupal::request()->query->get('destination'))) {
      $destination = \Drupal::request()->query->get('destination');
    }

    $redirect = new RedirectResponse($destination ?? '/');

    $user = \Drupal::currentUser();
    $account = Account::getByUser($user);

    if ($profile = UserProfile::loadByUser($user)) {
      \Drupal::logger('ohano_profile')->warning("UserProfile entity already exists.");
    }
    else {
      $profile = UserProfile::create()
        ->setAccount($account)
        ->setStatus(TRUE)
        ->setIsExcludedFromSearch(FALSE);
      $profile->save();
    }

    if ($baseProfile = BaseProfile::loadByProfile($profile)) {
      \Drupal::logger('ohano_profile')->warning("BaseProfile entity already exists.");
    }
    else {
      $baseProfile = BaseProfile::create()
        ->setUsername($user->getAccountName())
        ->setProfile($profile);
      $baseProfile->save();
    }

    return $redirect;
  }

  /**
   * Renders a profile based on its username.
   *
   * @param string|null $username
   *   The username of the profile to render.
   *
   * @return \Symfony\Component\HttpFoundation\RedirectResponse|array|\Symfony\Component\HttpFoundation\Response
   *   A redirect response to the own profile page if no username is given,
   *   an array of renderable elements if the profile exists, or a response
   *   containing an 404 if the profile could not be found.
   */
  public function profile(string $username = NULL): RedirectResponse|array|Response {
    $activeAccount = Account::forActive();
    if (empty($username) && !empty($activeAccount)) {
      $username = $activeAccount->getActiveProfile()->getProfileName();
      return new RedirectResponse("/user/$username");
    }

    $userProfile = UserProfile::loadByName($username);
    if (empty($userProfile)) {
      return new Response('', 404);
    }

    $renderedUserProfile = $userProfile->render();
    $renderedUserProfile['icon'] = Settings::PROFILE_TYPE_ICONS[$userProfile->getType()];

    /** @var \Drupal\ohano_profile\Entity\BaseProfile $baseProfile */
    $baseProfile = BaseProfile::loadByProfile($userProfile);

    $renderedBaseProfile = $baseProfile->render();
    $renderedBaseProfile['profile_picture'] = [
      '#theme' => 'profile_picture',
      '#image_url' => $renderedBaseProfile['profile_picture_url'],
      '#username' => $baseProfile->getUsername(),
    ];
    $renderedBaseProfile['has_general_info'] = FALSE;
    if ($baseProfile->getBirthday() || $baseProfile->getCity() || $baseProfile->getProvince() || $baseProfile->getCountry()) {
      $renderedBaseProfile['has_general_info'] = TRUE;
    }

    $renderedSocialProfile = NULL;
    if ($socialProfile = SocialMediaProfile::loadByProfile($userProfile)) {
      $renderedSocialProfile = $socialProfile->render();
    }

    $renderedGamingProfile = NULL;
    if ($gamingProfile = GamingProfile::loadByProfile($userProfile)) {
      $renderedGamingProfile = $gamingProfile->render();
    }

    $renderedRelationshipProfile = NULL;
    if ($relationshipProfile = RelationshipProfile::loadByProfile($userProfile)) {
      $renderedRelationshipProfile = $relationshipProfile->render();
    }

    $renderedJobProfile = NULL;
    if ($jobProfile = JobProfile::loadByProfile($userProfile)) {
      $renderedJobProfile = $jobProfile->render();
    }

    $renderedCodingProfile = NULL;
    if ($codingProfile = CodingProfile::loadByProfile($userProfile)) {
      $renderedCodingProfile = $codingProfile->render();
    }

    $isOwnProfile = FALSE;
    if (!empty($activeAccount)) {
      $profilesForAccount = UserProfile::loadMultipleByAccount($activeAccount);
      foreach ($profilesForAccount as $profile) {
        if ($profile->getProfileName() === $username) {
          $isOwnProfile = TRUE;
          break;
        }
      }
    }

    return [
      '#theme' => 'profile_page',
      '#profile' => [
        '#theme' => 'profile_card_large',
        '#profile' => [
          'user' => $renderedUserProfile,
          'base' => $renderedBaseProfile,
          'social' => $renderedSocialProfile,
          'gaming' => $renderedGamingProfile,
          'relationship' => $renderedRelationshipProfile,
          'job' => $renderedJobProfile,
          'coding' => $renderedCodingProfile,
        ],
      ],
      '#options' => [
        '#theme' => 'profile_options',
        '#own' => $isOwnProfile,
        '#name' => $userProfile->getProfileName(),
      ],
      '#cache' => [
        'max-age' => 0,
      ],
    ];
  }

  /**
   * Redirects to the profile page of the given username or user id.
   *
   * @param string|null $username
   *   The username of the profile to redirect to.
   * @param int|null $uid
   *   The user id of the profile to redirect to.
   *
   * @return \Symfony\Component\HttpFoundation\RedirectResponse
   *   A redirect response to the profile page of the given username or user id.
   */
  public function redirectToProfile(string $username = NULL, int $uid = NULL): RedirectResponse {
    if ($username) {
      return new RedirectResponse("/user/$username");
    }
    if ($uid) {
      return new RedirectResponse("/user/$uid");
    }

    return new RedirectResponse("/user");
  }

  /**
   * Lists all profiles for the current user.
   *
   * @return array
   *   A renderable array.
   */
  public function listProfiles(): array {
    $currentUser = \Drupal::currentUser();
    $account = Account::getByUser($currentUser);

    $profiles = \Drupal::entityQuery(UserProfile::ENTITY_ID)
      ->condition('account', $account->getId())
      ->execute();
    $renderableProfiles = [];
    foreach ($profiles as $profileId) {
      $userProfile = UserProfile::load($profileId);
      /** @var \Drupal\ohano_profile\Entity\BaseProfile $baseProfile */
      $baseProfile = BaseProfile::loadByProfile($userProfile);
      $imageUrl = NULL;
      if ($profilePicture = $baseProfile->getProfilePicture()) {
        $imageUrl = $profilePicture->createFileUrl(FALSE);
      }

      $bannerUrl = NULL;
      if ($profileBanner = $baseProfile->getProfileBanner()) {
        $bannerUrl = $profileBanner->createFileUrl(FALSE);
      }

      $renderable = [
        'name' => $userProfile->getProfileName(),
        'image_url' => $imageUrl,
        'type' => ProfileType::translatableFormOptions()[$userProfile->getType()],
        'icon' => Settings::PROFILE_TYPE_ICONS[$userProfile->getType()],
        'profile_banner' => $bannerUrl,
      ];

      if ($imageUrl) {
        $renderable['profile_picture'] = [
          '#theme' => 'profile_picture',
          '#image_url' => $imageUrl,
          '#username' => $userProfile->getProfileName(),
        ];
      }

      if ($account->getActiveProfile()->getId() == $userProfile->getId()) {
        $renderable['is_active'] = 1;
      }

      $renderableProfiles[] = $renderable;
    }

    // dd($renderableProfiles);
    return [
      '#theme' => 'profile_list',
      '#profiles' => $renderableProfiles,
      '#can_add' => 1,
      '#form' => \Drupal::formBuilder()->getForm(SwitchActiveProfileForm::class),
    ];
  }

  /**
   * Switches the active profile for the current user.
   *
   * @param string $profileName
   *   The name of the profile to switch to.
   *
   * @return \Symfony\Component\HttpFoundation\RedirectResponse
   *   A redirect response to the previous page.
   */
  public function switchActiveProfile(string $profileName): RedirectResponse {
    $redirect = new RedirectResponse(\Drupal::request()->headers->get('referer'));
    $account = Account::forActive();
    $profile = UserProfile::loadByName($profileName);

    if (empty($profile)) {
      $this->messenger()->addError($this->t("This profile could not be found."));
      return $redirect;
    }

    if ($profile->getAccount()->getId() !== $account->getId()) {
      $this->messenger()->addError($this->t("This isn't your profile."));
      return $redirect;
    }

    $account->setActiveProfile($profile);
    try {
      $account->save();
    }
    catch (EntityStorageException $e) {
      $this->messenger()->addError($this->t("Something went wrong."));
      $this->getLogger('ohano_profile')->critical($e->getMessage());
      return $redirect;
    }

    $this->messenger()->addMessage($this->t("Welcome back @user!", ['@user' => $profile->getProfileName()]));
    return $redirect;
  }

}
