<?php

namespace Drupal\ohano_profile\Form;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Entity\EntityStorageException;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\file\Entity\File;
use Drupal\ohano_account\Blocklist;
use Drupal\ohano_account\Entity\Account;
use Drupal\ohano_core\Error\Error;
use Drupal\ohano_core\Form\FormTrait;
use Drupal\ohano_core\OhanoCore;
use Drupal\ohano_profile\Entity\BaseProfile;
use Drupal\ohano_profile\Entity\CodingProfile;
use Drupal\ohano_profile\Entity\GamingProfile;
use Drupal\ohano_profile\Entity\JobProfile;
use Drupal\ohano_profile\Entity\RelationshipProfile;
use Drupal\ohano_profile\Entity\SocialMediaProfile;
use Drupal\ohano_profile\Entity\UserProfile;
use Drupal\ohano_profile\Option\EducationDegree;
use Drupal\ohano_profile\Option\EmploymentStatus;
use Drupal\ohano_profile\Option\Gender;
use Drupal\ohano_profile\Option\ProfileType;
use Drupal\ohano_profile\Option\RelationshipStatus;
use Drupal\ohano_profile\Option\RelationshipType;
use Drupal\ohano_profile\Option\Sexuality;
use Drupal\taxonomy\Entity\Term;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ProfileForm extends FormBase {
  use FormTrait;

  public function getFormId() {
    return 'ohano_profile_profile';
  }

  public function buildForm(array $form, FormStateInterface $form_state, string $profileName = NULL) {
    $form = [];

    if (empty($profileName)) {
      $this->messenger()->addError($this->t("Oops, that didn't work."));
      (new RedirectResponse('/profile'))->send();
    }

    $confirmationDelete = $this->t('Are you sure? Any saved data will be lost.');
    $currentUser = \Drupal::currentUser();
    $account = Account::getByUser($currentUser);

    $userProfile = UserProfile::loadByName($profileName);
    if (empty($userProfile)) {
      (new RedirectResponse('/profile/create-base?destination=/profile/edit'))->send();
    }

    if ($account->getId() != $userProfile->getAccount()->getId()) {
      $this->messenger()->addError($this->t('You are not allowed to edit this profile.'));
      (new RedirectResponse('/user/' . $userProfile->getProfileName()))->send();
    }

    /** @var BaseProfile $baseProfile */
    $baseProfile = BaseProfile::loadByProfile($userProfile);
    if (empty($baseProfile)) {
      $this->messenger()->addError($this->t("Oops, that looks wrong. We're sorry about that. Please contact the support with the following error code: @error", ['@error' => Error::BaseProfileNotFound->value]));
      return [];
    }

    $form['profile_name'] = [
      '#type' => 'hidden',
      '#value' => $userProfile->getProfileName(),
    ];

    $form['back'] = [
      '#type' => 'markup',
      '#markup' => '<div><a class="button" href="/user/' . $userProfile->getProfileName() . '"><i class="fa fa-angle-left"></i>&nbsp;' . $this->t('Back to your profile') . '</a></div><br />',
    ];

    $form['user_profile'] = $this->buildDefaultContainer($this->t('General'), TRUE);
    $form['user_profile']['profile_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Profile name'),
      '#default_value' => $userProfile->getProfileName(),
    ];

    if ($userProfile->getType() == ProfileType::Personal->value) {
      $form['user_profile']['profile_name']['#attributes'] = [
        'disabled' => [
          'disabled',
        ],
      ];
      $form['user_profile']['profile_name']['#description'] = $this->t("You can't change the name of your personal profile. To change your username, head over to your account settings.");
    }

    $form['user_profile']['type'] = [
      '#type' => 'select',
      '#title' => $this->t('Profile type'),
      '#options' => ProfileType::translatableFormOptions(),
      '#default_value' => $userProfile->getType(),
      '#attributes' => [
        'disabled' => 'disabled',
      ],
    ];

    $form['base_profile'] = $this->buildDefaultContainer($this->t('Base Profile'), TRUE);
    $form['base_profile'] += BaseProfile::renderForm($baseProfile);

    $form['social_profile'] = $this->buildDefaultContainer($this->t('Social media Profile'));
    /** @var SocialMediaProfile $socialProfile */
    if ($socialProfile = SocialMediaProfile::loadByProfile($userProfile)) {
      $form['social_profile'] += SocialMediaProfile::renderForm($socialProfile);
      $form['social_profile']['delete'] = [
        '#type' => 'submit',
        '#value' => $this->t('Delete social media profile'),
        '#submit' => [
          '::deleteSocialMediaProfile',
        ],
        '#attributes' => [
          'onclick' => [
            'return confirm("' . $confirmationDelete->render() . '");'
          ],
          'class' => [
            'delete-button',
          ],
        ],
      ];

    }
    else {
      $form['social_profile']['create'] = [
        '#type' => 'submit',
        '#value' => $this->t('Create social media profile'),
        '#submit' => [
          '::createSocialMediaProfile',
        ],
        '#attributes' => [
          'class' => [
            'create-button',
          ],
        ],
      ];
    }

    $form['relationship_profile'] = $this->buildDefaultContainer($this->t('Relationship Profile'));

    /** @var RelationshipProfile $relationshipProfile */
    if ($relationshipProfile = RelationshipProfile::loadByProfile($userProfile)) {
      $form['relationship_profile'] += RelationshipProfile::renderForm($relationshipProfile);
      $form['relationship_profile']['delete'] = [
        '#type' => 'submit',
        '#value' => $this->t('Delete relationship profile'),
        '#submit' => [
          '::deleteRelationshipProfile',
        ],
        '#attributes' => [
          'onclick' => [
            'return confirm("' . $confirmationDelete->render() . '");'
          ],
          'class' => [
            'delete-button',
          ],
        ],
      ];
    }
    else {
      $form['relationship_profile']['create'] = [
        '#type' => 'submit',
        '#value' => $this->t('Create relationship profile'),
        '#submit' => [
          '::createRelationshipProfile',
        ],
        '#attributes' => [
          'class' => [
            'create-button',
          ],
        ],
      ];
    }

    $form['job_profile'] = $this->buildDefaultContainer($this->t('Job Profile'));

    /** @var JobProfile $jobProfile */
    if ($jobProfile = JobProfile::loadByProfile($userProfile)) {
      $form['job_profile'] += JobProfile::renderForm($jobProfile);
      $form['job_profile']['delete'] = [
        '#type' => 'submit',
        '#value' => $this->t('Delete job profile'),
        '#submit' => [
          '::deleteJobProfile',
        ],
        '#attributes' => [
          'onclick' => [
            'return confirm("' . $confirmationDelete->render() . '");'
          ],
          'class' => [
            'delete-button',
          ],
        ],
      ];
    }
    else {
      $form['job_profile']['create'] = [
        '#type' => 'submit',
        '#value' => $this->t('Create job profile'),
        '#submit' => [
          '::createJobProfile',
        ],
        '#attributes' => [
          'class' => [
            'create-button',
          ],
        ],
      ];
    }

    $form['gaming_profile'] = $this->buildDefaultContainer($this->t('Gaming Profile'));

    /** @var GamingProfile $gamingProfile */
    if ($gamingProfile = GamingProfile::loadByProfile($userProfile)) {
      $form['gaming_profile'] += GamingProfile::renderForm($gamingProfile);
      $form['gaming_profile']['delete'] = [
        '#type' => 'submit',
        '#value' => $this->t('Delete gaming profile'),
        '#submit' => [
          '::deleteGamingProfile',
        ],
        '#attributes' => [
          'onclick' => [
            'return confirm("' . $confirmationDelete->render() . '");'
          ],
          'class' => [
            'delete-button',
          ],
        ],
      ];
    }
    else {
      $form['gaming_profile']['create'] = [
        '#type' => 'submit',
        '#value' => $this->t('Create gaming profile'),
        '#submit' => [
          '::createGamingProfile',
        ],
        '#attributes' => [
          'class' => [
            'create-button',
          ],
        ],
      ];
    }

    $form['coding_profile'] = $this->buildDefaultContainer($this->t('Coding Profile'));

    /** @var CodingProfile $codingProfile */
    if ($codingProfile = CodingProfile::loadByProfile($userProfile)) {
      $form['coding_profile'] += CodingProfile::renderForm($codingProfile);
      $form['coding_profile']['delete'] = [
        '#type' => 'submit',
        '#value' => $this->t('Delete coding profile'),
        '#submit' => [
          '::deleteCodingProfile',
        ],
        '#attributes' => [
          'onclick' => [
            'return confirm("' . $confirmationDelete->render() . '");'
          ],
          'class' => [
            'delete-button',
          ],
        ],
      ];
    }
    else {
      $form['coding_profile']['create'] = [
        '#type' => 'submit',
        '#value' => $this->t('Create coding profile'),
        '#submit' => [
          '::createCodingProfile',
        ],
        '#attributes' => [
          'class' => [
            'create-button',
          ],
        ],
      ];
    }

    if ($currentUser->hasPermission('ohano bulk create sub profiles')) {
      $form['actions']['create_all'] = [
        '#type' => 'submit',
        '#value' => $this->t('Create all sub profiles'),
        '#submit' => [
          '::createAllProfiles',
        ],
        '#attributes' => [
          'class' => [
            'create-button',
          ],
        ],
      ];
    }

    if ($currentUser->hasPermission('ohano bulk delete sub profiles')) {
      $form['actions']['delete_all'] = [
        '#type' => 'submit',
        '#value' => $this->t('Delete all sub profiles'),
        '#submit' => [
          '::deleteAllProfiles',
        ],
        '#attributes' => [
          'onclick' => [
            'return confirm("' . $confirmationDelete->render() . '");'
          ],
          'class' => [
            'delete-button',
          ],
        ],
      ];
    }

    if ($userProfile->getType() !== ProfileType::Personal->value) {
      $form['actions']['delete'] = [
        '#type' => 'submit',
        '#value' => $this->t('Delete whole profile'),
        '#submit' => [
          '::deleteWholeProfile',
        ],
        '#attributes' => [
          'onclick' => [
            'return confirm("' . $confirmationDelete->render() . '");'
          ],
          'class' => [
            'delete-button',
          ],
        ],
      ];
    }

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save all'),
    ];

    return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
    $formValues = $form_state->getValues();

    $profileName = $formValues['profile_name'];
    $account = Account::forActive();
    $profile = UserProfile::loadByName($profileName);
    if ($profile->getAccount()->getId() !== $account->getId()) {
      $form_state->setErrorByName('profile_name', $this->t("Seems like you tried to edit a profile which is not yours."));
    }

    $user = \Drupal::currentUser();
    // Validate real name.
    $realname = $formValues['base_profile']['real_name'];
    if (!empty($realname)) {
      if (in_array($realname, Blocklist::USERNAME) && !$user->hasPermission('non existent admin permission')) {
        $form_state->setErrorByName('real_name', $this->t('Your real name contains forbidden parts.'));
      }
    }

    // Validate birthday.
    $birthday = $formValues['base_profile']['birthday'];
    if (!empty($birthday)) {
      $now = new DrupalDateTime();
      $minimum = $now->modify('-18 years');
      $minimum = (int) $minimum->format('U');
      $birthday = DrupalDateTime::createFromFormat('Y-m-d', $birthday);
      $birthday = (int) $birthday->format('U');

      if ($birthday > $minimum) {
        $form_state->setErrorByName('birthday', $this->t('Your birthday cannot be less than 18 years ago.'));
      }
    }

    parent::validateForm($form, $form_state); // TODO: Change the autogenerated stub
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $currentUser = \Drupal::currentUser();
    $values = $form_state->getValues();
    $userProfile = UserProfile::loadByName($values['profile_name']);

    /** @var BaseProfile $baseProfile */
    $baseProfile = BaseProfile::loadByProfile($userProfile);
    $baseProfile->setRealname($values['base_profile']['real_name']);
    if (isset($values['base_profile']['profile_picture'][0]) && !empty($values['base_profile']['profile_picture'][0])) {
      $file = File::load($values['base_profile']['profile_picture'][0]);
      $file->setPermanent();
      try {
        $file->save();
        $baseProfile->setProfilePicture($file);
      } catch (EntityStorageException $e) {
        \Drupal::messenger()->addError('Profile could not be saved due to technical errors.');
        \Drupal::logger('ohano_profile')->critical($e->getMessage());
      }
    } else {
      $baseProfile->setProfilePicture();
    }
    if (isset($values['base_profile']['profile_banner'][0]) && !empty($values['base_profile']['profile_banner'][0])) {
      $file = File::load($values['base_profile']['profile_banner'][0]);
      $file->setPermanent();
      try {
        $file->save();
        $baseProfile->setProfileBanner($file);
      } catch (EntityStorageException $e) {
        \Drupal::messenger()->addError('Profile could not be saved due to technical errors.');
        \Drupal::logger('ohano_profile')->critical($e->getMessage());
      }
    } else {
      $baseProfile->setProfileBanner();
    }
    $baseProfile->setProfileText($values['base_profile']['profile_text']);
    if (!empty($values['base_profile']['birthday'])) {
      $birthday = DrupalDateTime::createFromFormat('Y-m-d', $values['base_profile']['birthday']);
    } else {
      $birthday = NULL;
    }
    $baseProfile->setBirthday($birthday);
    $baseProfile->setGender(Gender::tryFrom($values['base_profile']['gender']));
    $baseProfile->setCity($values['base_profile']['city']);
    $baseProfile->setProvince($values['base_profile']['province']);
    $baseProfile->setCountry($values['base_profile']['country']);

    $baseProfile->save();

    if (!isset($form['social_profile']['create'])) {
      /** @var SocialMediaProfile $socialProfile */
      $socialProfile = SocialMediaProfile::loadByProfile($userProfile);
      $socialProfile->setTwitter($values['social_profile']['twitter']);
      $socialProfile->setTwitch($values['social_profile']['twitch']);
      $socialProfile->setFacebook($values['social_profile']['facebook']);
      $socialProfile->setInstagram($values['social_profile']['instagram']);
      $socialProfile->setLinkedin($values['social_profile']['linkedin']);
      $socialProfile->setXing($values['social_profile']['xing']);
      $socialProfile->setDiscord($values['social_profile']['discord']);
      $socialProfile->setBehance($values['social_profile']['behance']);
      $socialProfile->setDribbble($values['social_profile']['dribbble']);
      $socialProfile->setPinterest($values['social_profile']['pinterest']);

      $socialProfile->save();
    }

    if (!isset($form['relationship_profile']['create'])) {
      /** @var RelationshipProfile $relationshipProfile */
      $relationshipProfile = RelationshipProfile::loadByProfile($userProfile);
      $relationshipProfile->setRelationshipStatus(RelationshipStatus::tryFrom($values['relationship_profile']['status']));
      $relationshipProfile->setRelationshipType(RelationshipType::tryFrom($values['relationship_profile']['type']));
      $relationshipProfile->setSexuality(Sexuality::tryFrom($values['relationship_profile']['sexuality']));

      $relationshipProfile->save();
    }

    if (!isset($form['job_profile']['create'])) {
      /** @var JobProfile $jobProfile */
      $jobProfile = JobProfile::loadByProfile($userProfile);
      $jobProfile->setEducationDegree(EducationDegree::tryFrom($values['job_profile']['education_degree']));
      $jobProfile->setEmploymentStatus(EmploymentStatus::tryFrom($values['job_profile']['employment_status']));
      $jobProfile->setEmployer($values['job_profile']['employer']);
      $jobProfile->setIndustry($values['job_profile']['industry']);
      $jobProfile->setPosition($values['job_profile']['position']);

      $jobProfile->save();
    }

    if (!isset($form['gaming_profile']['create'])) {
      /** @var GamingProfile $gamingProfile */
      $gamingProfile = GamingProfile::loadByProfile($userProfile);
      $gamingProfile->setMinecraftName($values['gaming_profile']['minecraft_name']);
      if (!empty($values['gaming_profile']['minecraft_name'])) {
        $minecraftUuid = shell_exec("curl https://api.mojang.com/users/profiles/minecraft/{$values['gaming_profile']['minecraft_name']}");
        if (!empty($minecraftUuid)) {
          $minecraftUuid = json_decode($minecraftUuid, TRUE);
          if (!empty($minecraftUuid['id'])) {
            $gamingProfile->setMinecraftUuid($minecraftUuid['id']);
          } else {
            $gamingProfile->setMinecraftUuid();
          }
        } else {
          $gamingProfile->setMinecraftUuid();
        }
      }
      $gamingProfile->setValorant($values['gaming_profile']['valorant']);
      $gamingProfile->setLeagueOfLegends($values['gaming_profile']['league_of_legends']);
      $gamingProfile->setBattleNet($values['gaming_profile']['battle_net']);
      $gamingProfile->setUbisoftConnect($values['gaming_profile']['ubisoft_connect']);
      $gamingProfile->setSteam($values['gaming_profile']['steam']);
      $gamingProfile->setEaOrigin($values['gaming_profile']['ea_origin']);
      $gamingProfile->setGames(Term::loadMultiple($values['gaming_profile']['games']));
      $gamingProfile->setPlatforms(Term::loadMultiple($values['gaming_profile']['platforms']));

      $gamingProfile->save();
    }

    if (!isset($form['coding_profile']['create'])) {
      /** @var CodingProfile $codingProfile */
      $codingProfile = CodingProfile::loadByProfile($userProfile);
      $codingProfile->setGithub($values['coding_profile']['github']);
      $codingProfile->setGitlab($values['coding_profile']['gitlab']);
      $codingProfile->setBitbucket($values['coding_profile']['bitbucket']);
      $codingProfile->setProgrammingLanguages(Term::loadMultiple($values['coding_profile']['programming_languages']));
      $codingProfile->setSystems(Term::loadMultiple($values['coding_profile']['systems']));

      $codingProfile->save();
    }

    $this->messenger()->addMessage($this->t('Saved successfully!'));
  }

  public function deleteWholeProfile(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    $profileName = $values['profile_name'];
    /** @var UserProfile $userProfile */
    $userProfile = UserProfile::loadByName($profileName);
    $currentUser = \Drupal::currentUser();

    if ($userProfile->getAccount()->getUser()->id() == $currentUser->id() || $currentUser->hasPermission('ohano delete every profile')) {
      if ($userProfile->getType() !== ProfileType::Personal->value) {
        $personalProfile = UserProfile::loadByName($currentUser->getAccountName());
        $account = Account::getByUser($currentUser);
        if ($account->getActiveProfile()->id() == $userProfile->id()) {
          $account->setActiveProfile($personalProfile);
          $account->save();
        }

        $userProfile->delete();
        $this->messenger()->addMessage($this->t("Profile with name @name has been deleted successfully.", ['@name' => $profileName]));
        $form_state->setRedirect('ohano_profile.profile.self');
      }
    }
  }

  public function createAllProfiles(array &$form, FormStateInterface $form_state) {
    $this->createCodingProfile($form, $form_state);
    $this->createGamingProfile($form, $form_state);
    $this->createJobProfile($form, $form_state);
    $this->createRelationshipProfile($form, $form_state);
    $this->createSocialMediaProfile($form, $form_state);
  }

  public function deleteAllProfiles(array &$form, FormStateInterface $form_state) {
    $this->deleteCodingProfile($form, $form_state);
    $this->deleteGamingProfile($form, $form_state);
    $this->deleteJobProfile($form, $form_state);
    $this->deleteRelationshipProfile($form, $form_state);
    $this->deleteSocialMediaProfile($form, $form_state);
  }

  public function createSocialMediaProfile(array &$form, FormStateInterface $form_state) {
    $profileName = $form_state->getValue('profile_name');
    $userProfile = UserProfile::loadByName($profileName);
    if (empty(SocialMediaProfile::loadByProfile($userProfile))) {
      SocialMediaProfile::create()
        ->setProfile($userProfile)
        ->save();

      $this->messenger()->addMessage($this->t('Successfully created social media profile!'));
    }
  }

  public function deleteSocialMediaProfile(array &$form, FormStateInterface $form_state) {
    $profileName = $form_state->getValue('profile_name');
    $userProfile = UserProfile::loadByName($profileName);
    if (!empty($profile = SocialMediaProfile::loadByProfile($userProfile))) {
      $profile->delete();

      $this->messenger()->addMessage($this->t('Successfully deleted social media profile!'));
    }
  }

  public function createRelationshipProfile(array &$form, FormStateInterface $form_state) {
    $profileName = $form_state->getValue('profile_name');
    $userProfile = UserProfile::loadByName($profileName);
    if (empty(RelationshipProfile::loadByProfile($userProfile))) {
      RelationshipProfile::create()
        ->setProfile($userProfile)
        ->save();

      $this->messenger()->addMessage($this->t('Successfully created relationship profile!'));
    }
  }

  public function deleteRelationshipProfile(array &$form, FormStateInterface $form_state) {
    $profileName = $form_state->getValue('profile_name');
    $userProfile = UserProfile::loadByName($profileName);
    if (!empty($profile = RelationshipProfile::loadByProfile($userProfile))) {
      $profile->delete();

      $this->messenger()->addMessage($this->t('Successfully deleted relationship profile!'));
    }
  }

  public function createJobProfile(array &$form, FormStateInterface $form_state) {
    $profileName = $form_state->getValue('profile_name');
    $userProfile = UserProfile::loadByName($profileName);
    if (empty(JobProfile::loadByProfile($userProfile))) {
      JobProfile::create()
        ->setProfile($userProfile)
        ->save();

      $this->messenger()->addMessage($this->t('Successfully created job profile!'));
    }
  }

  public function deleteJobProfile(array &$form, FormStateInterface $form_state) {
    $profileName = $form_state->getValue('profile_name');
    $userProfile = UserProfile::loadByName($profileName);
    if (!empty($profile = JobProfile::loadByProfile($userProfile))) {
      $profile->delete();

      $this->messenger()->addMessage($this->t('Successfully deleted job profile!'));
    }
  }

  public function createGamingProfile(array &$form, FormStateInterface $form_state) {
    $profileName = $form_state->getValue('profile_name');
    $userProfile = UserProfile::loadByName($profileName);
    if (empty(GamingProfile::loadByProfile($userProfile))) {
      GamingProfile::create()
        ->setProfile($userProfile)
        ->save();

      $this->messenger()->addMessage($this->t('Successfully created gaming profile!'));
    }
  }

  public function deleteGamingProfile(array &$form, FormStateInterface $form_state) {
    $profileName = $form_state->getValue('profile_name');
    $userProfile = UserProfile::loadByName($profileName);
    if (!empty($profile = GamingProfile::loadByProfile($userProfile))) {
      $profile->delete();

      $this->messenger()->addMessage($this->t('Successfully deleted gaming profile!'));
    }
  }

  public function createCodingProfile(array &$form, FormStateInterface $form_state) {
    $profileName = $form_state->getValue('profile_name');
    $userProfile = UserProfile::loadByName($profileName);
    if (empty(CodingProfile::loadByProfile($userProfile))) {
      CodingProfile::create()
        ->setProfile($userProfile)
        ->save();

      $this->messenger()->addMessage($this->t('Successfully created coding profile!'));
    }
  }

  public function deleteCodingProfile(array &$form, FormStateInterface $form_state) {
    $profileName = $form_state->getValue('profile_name');
    $userProfile = UserProfile::loadByName($profileName);
    if (!empty($profile = CodingProfile::loadByProfile($userProfile))) {
      $profile->delete();

      $this->messenger()->addMessage($this->t('Successfully deleted coding profile!'));
    }
  }

}
