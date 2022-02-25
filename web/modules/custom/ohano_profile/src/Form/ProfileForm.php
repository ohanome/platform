<?php

namespace Drupal\ohano_profile\Form;

use Drupal\Core\Datetime\DrupalDateTime;
use Drupal\Core\Entity\EntityStorageException;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\file\Entity\File;
use Drupal\ohano_account\Blocklist;
use Drupal\ohano_core\Error\Error;
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
use Drupal\ohano_profile\Option\RelationshipStatus;
use Drupal\ohano_profile\Option\RelationshipType;
use Drupal\ohano_profile\Option\Sexuality;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ProfileForm extends FormBase {

  public function getFormId() {
    return 'ohano_profile_profile';
  }

  protected function buildDefaultContainer(TranslatableMarkup|string $title, bool $open = TRUE) {
    return [
      '#type' => 'details',
      '#open' => $open,
      '#tree' => TRUE,
      '#title' => $title,
    ];
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = [];

    $confirmationDelete = $this->t('Are you sure? Any saved data will be lost.');
    $currentUser = \Drupal::currentUser();
    $userProfile = UserProfile::loadByUser($currentUser);
    if (empty($userProfile)) {
      (new RedirectResponse('/profile/create-base?destination=/profile/edit'))->send();
    }

    /** @var BaseProfile $baseProfile */
    $baseProfile = BaseProfile::loadByProfile($userProfile);
    if (empty($baseProfile)) {
      $this->messenger()->addError($this->t("Oops, that looks wrong. We're sorry about that. Please contact the support with the following error code: @error", ['@error' => Error::BaseProfileNotFound->value]));
      return [];
    }

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
      ];
    }

    $form['actions']['create_all'] = [
      '#type' => 'submit',
      '#value' => $this->t('Create all profiles'),
      '#submit' => [
        '::createAllProfiles',
      ],
    ];

    $form['actions']['delete_all'] = [
      '#type' => 'submit',
      '#value' => $this->t('Delete all profiles'),
      '#submit' => [
        '::deleteAllProfiles',
      ],
      '#attributes' => [
        'onclick' => [
          'return confirm("' . $confirmationDelete->render() . '");'
        ],
      ],
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save all'),
    ];

    return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
    $formValues = $form_state->getValues();

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
    $userProfile = UserProfile::loadByUser($currentUser);
    $values = $form_state->getValues();

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
    $baseProfile->setBirthday(DrupalDateTime::createFromFormat('Y-m-d', $values['base_profile']['birthday']));
    $baseProfile->setGender(Gender::tryFrom($values['base_profile']['gender']));
    $baseProfile->setCity($values['base_profile']['city']);
    $baseProfile->setProvince($values['base_profile']['province']);
    $baseProfile->setCountry($values['base_profile']['country']);

    $baseProfile->save();

    if (!isset($form['social_profile']['create'])) {
      /** @var SocialMediaProfile $socialProfile */
      $socialProfile = SocialMediaProfile::loadByProfile($userProfile);
      $socialProfile->setTwitter($values['social_profile']['twitter']);
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
      $relationshipProfile->setRelationshipStatus(RelationshipStatus::tryFrom($values['relationship_profile']['relationship_status']));
      $relationshipProfile->setRelationshipType(RelationshipType::tryFrom($values['relationship_profile']['relationship_type']));
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
      $gamingProfile->setGames($values['gaming_profile']['games']);
      $gamingProfile->setPlatforms($values['gaming_profile']['platforms']);

      $gamingProfile->save();
    }

    if (!isset($form['coding_profile']['create'])) {
      /** @var CodingProfile $codingProfile */
      $codingProfile = CodingProfile::loadByProfile($userProfile);
      $codingProfile->setGithub($values['coding_profile']['github']);
      $codingProfile->setGitlab($values['coding_profile']['gitlab']);
      $codingProfile->setBitbucket($values['coding_profile']['bitbucket']);
      $codingProfile->setProgrammingLanguages($values['coding_profile']['programming_languages']);
      $codingProfile->setSystems($values['coding_profile']['systems']);

      $codingProfile->save();
    }

    $this->messenger()->addMessage($this->t('Saved successfully!'));
  }

  public function createAllProfiles() {
    $this->createCodingProfile();
    $this->createGamingProfile();
    $this->createJobProfile();
    $this->createRelationshipProfile();
    $this->createSocialMediaProfile();
  }

  public function deleteAllProfiles() {
    $this->deleteCodingProfile();
    $this->deleteGamingProfile();
    $this->deleteJobProfile();
    $this->deleteRelationshipProfile();
    $this->deleteSocialMediaProfile();
  }

  public function createSocialMediaProfile() {
    if (empty(SocialMediaProfile::loadByProfile(UserProfile::loadByUser(\Drupal::currentUser())))) {
      SocialMediaProfile::create()
        ->setProfile(UserProfile::loadByUser(\Drupal::currentUser()))
        ->save();

      $this->messenger()->addMessage($this->t('Successfully created social media profile!'));
    }
  }

  public function deleteSocialMediaProfile() {
    if (!empty($profile = SocialMediaProfile::loadByProfile(UserProfile::loadByUser(\Drupal::currentUser())))) {
      $profile->delete();

      $this->messenger()->addMessage($this->t('Successfully deleted social media profile!'));
    }
  }

  public function createRelationshipProfile() {
    if (empty(RelationshipProfile::loadByProfile(UserProfile::loadByUser(\Drupal::currentUser())))) {
      RelationshipProfile::create()
        ->setProfile(UserProfile::loadByUser(\Drupal::currentUser()))
        ->save();

      $this->messenger()->addMessage($this->t('Successfully created relationship profile!'));
    }
  }

  public function deleteRelationshipProfile() {
    if (!empty($profile = RelationshipProfile::loadByProfile(UserProfile::loadByUser(\Drupal::currentUser())))) {
      $profile->delete();

      $this->messenger()->addMessage($this->t('Successfully deleted relationship profile!'));
    }
  }

  public function createJobProfile() {
    if (empty(JobProfile::loadByProfile(UserProfile::loadByUser(\Drupal::currentUser())))) {
      JobProfile::create()
        ->setProfile(UserProfile::loadByUser(\Drupal::currentUser()))
        ->save();

      $this->messenger()->addMessage($this->t('Successfully created job profile!'));
    }
  }

  public function deleteJobProfile() {
    if (!empty($profile = JobProfile::loadByProfile(UserProfile::loadByUser(\Drupal::currentUser())))) {
      $profile->delete();

      $this->messenger()->addMessage($this->t('Successfully deleted job profile!'));
    }
  }

  public function createGamingProfile() {
    if (empty(GamingProfile::loadByProfile(UserProfile::loadByUser(\Drupal::currentUser())))) {
      GamingProfile::create()
        ->setProfile(UserProfile::loadByUser(\Drupal::currentUser()))
        ->save();

      $this->messenger()->addMessage($this->t('Successfully created gaming profile!'));
    }
  }

  public function deleteGamingProfile() {
    if (!empty($profile = GamingProfile::loadByProfile(UserProfile::loadByUser(\Drupal::currentUser())))) {
      $profile->delete();

      $this->messenger()->addMessage($this->t('Successfully deleted gaming profile!'));
    }
  }

  public function createCodingProfile() {
    if (empty(CodingProfile::loadByProfile(UserProfile::loadByUser(\Drupal::currentUser())))) {
      CodingProfile::create()
        ->setProfile(UserProfile::loadByUser(\Drupal::currentUser()))
        ->save();

      $this->messenger()->addMessage($this->t('Successfully created coding profile!'));
    }
  }

  public function deleteCodingProfile() {
    if (!empty($profile = CodingProfile::loadByProfile(UserProfile::loadByUser(\Drupal::currentUser())))) {
      $profile->delete();

      $this->messenger()->addMessage($this->t('Successfully deleted coding profile!'));
    }
  }

}
