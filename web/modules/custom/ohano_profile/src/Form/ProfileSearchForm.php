<?php

namespace Drupal\ohano_profile\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\ohano_account\Entity\Account;
use Drupal\ohano_core\Form\FormTrait;
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
use Drupal\taxonomy\Entity\Vocabulary;

class ProfileSearchForm extends FormBase {
  use FormTrait;

  public function getFormId() {
    return 'ohano_profile_profile_search';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $account = Account::forActive();

    $userProfile = $account->getActiveProfile();
    /** @var BaseProfile $baseProfile */
    $baseProfile = BaseProfile::loadByProfile($userProfile);
    /** @var CodingProfile $codingProfile */
    $codingProfile = CodingProfile::loadByProfile($userProfile);
    /** @var GamingProfile $gamingProfile */
    $gamingProfile = GamingProfile::loadByProfile($userProfile);
    /** @var JobProfile $jobProfile */
    $jobProfile = JobProfile::loadByProfile($userProfile);
    /** @var RelationshipProfile $relationshipProfile */
    $relationshipProfile = RelationshipProfile::loadByProfile($userProfile);
    /** @var SocialMediaProfile $socialMediaProfile */
    $socialMediaProfile = SocialMediaProfile::loadByProfile($userProfile);

    $form = [];

    $form['to_simple'] = [
      '#type' => 'markup',
      '#markup' => '<a href="#">' . $this->t('To the simple search') . '</a><br />',
    ];

    $canSearchAll = \Drupal::currentUser()->hasPermission('ohano search user by all fields');

    $form['base'] = $this->buildDefaultContainer($this->t('Basic information'), TRUE);
    $form['base']['profile_name'] = $this->buildTextField($this->t('User- / Profile name'));

    if ($baseProfile?->getRealName() || $canSearchAll) {
      $form['base']['real_name'] = $this->buildTextField($this->t('Name'));;
    }
    if ($baseProfile?->getGender() || $canSearchAll) {
      $form['base']['gender'] = $this->buildSelectField($this->t('Gender'), Gender::translatableFormOptions(), useChosen: TRUE, multi: TRUE);
    }
    if ($baseProfile?->getCity() || $canSearchAll) {
      $form['base']['city'] = $this->buildTextField($this->t('City'));
    }
    if ($baseProfile?->getProvince() || $canSearchAll) {
      $form['base']['province'] = $this->buildTextField($this->t('State or province'));
    }
    if ($baseProfile?->getCountry() || $canSearchAll) {
      $form['base']['country'] = $this->buildTextField($this->t('Country'));
    }

    if ($codingProfile || $canSearchAll) {
      $form['coding'] = $this->buildDefaultContainer($this->t("Coding"), TRUE);
      if ($codingProfile?->getGithub() || $canSearchAll) {
        $form['coding']['github'] = $this->buildTextField($this->t('GitHub'));
      }
      if ($codingProfile?->getGitlab() || $canSearchAll) {
        $form['coding']['gitlab'] = $this->buildTextField($this->t('GitLab'));
      }
      if ($codingProfile?->getBitbucket() || $canSearchAll) {
        $form['coding']['bitbucket'] = $this->buildTextField($this->t('Bitbucket'));
      }
      if (empty($codingProfile?->getProgrammingLanguages()) || $canSearchAll) {
        $form['coding']['programming_languages'] = $this->buildTermBasedSelect('programming_languages', $this->t('Programming languages'), TRUE);
      }
      if (empty($codingProfile?->getSystems()) || $canSearchAll) {
        $form['coding']['systems'] =  $this->buildTermBasedSelect('systems', $this->t('Systems'), TRUE);
      }
    }

    if ($gamingProfile || $canSearchAll) {
      $form['gaming'] = $this->buildDefaultContainer($this->t('Gaming'));
      if ($gamingProfile?->getMinecraftName() || $canSearchAll) {
        $form['gaming']['minecraft_name'] = $this->buildTextField($this->t('Minecraft name'));
      }
      if ($gamingProfile?->getValorant() || $canSearchAll) {
        $form['gaming']['valorant'] = $this->buildTextField($this->t('VALORANT'));
      }
      if ($gamingProfile?->getLeagueOfLegends() || $canSearchAll) {
        $form['gaming']['league_of_legends'] = $this->buildTextField($this->t('League of Legends'));
      }
      if ($gamingProfile?->getBattleNet() || $canSearchAll) {
        $form['gaming']['battle_net'] = $this->buildTextField($this->t('Battle.net'));
      }
      if ($gamingProfile?->getUbisoftConnect() || $canSearchAll) {
        $form['gaming']['ubisoft_connect'] = $this->buildTextField($this->t('Ubisoft Connect'));
      }
      if ($gamingProfile?->getSteam() || $canSearchAll) {
        $form['gaming']['steam'] = $this->buildTextField($this->t('Steam'));
      }
      if ($gamingProfile?->getEaOrigin() || $canSearchAll) {
        $form['gaming']['ea_origin'] = $this->buildTextField($this->t('EA Origin'));
      }
      if (empty($gamingProfile?->getGames()) || $canSearchAll) {
        $form['gaming']['games'] = $this->buildTermBasedSelect('games', $this->t('Games'), TRUE);
      }
      if (empty($gamingProfile?->getPlatforms()) || $canSearchAll) {
        $form['gaming']['platforms'] = $this->buildTermBasedSelect('gaming_platforms', $this->t('Platforms'), TRUE);
      }
    }

    if ($jobProfile || $canSearchAll) {
      $form['job'] = $this->buildDefaultContainer($this->t('Job'));
      if ($jobProfile?->getEducationDegree() || $canSearchAll) {
        $form['job']['education_degree'] = $this->buildSelectField($this->t('Education degree'), EducationDegree::translatableFormOptions(), useChosen: TRUE, multi: TRUE);
      }
      if ($jobProfile?->getEmploymentStatus() || $canSearchAll) {
        $form['job']['employment_status'] = $this->buildSelectField($this->t('Employment status'), EmploymentStatus::translatableFormOptions(), useChosen: TRUE, multi: TRUE);
      }
      if ($jobProfile?->getPosition() || $canSearchAll) {
        $form['job']['position'] = $this->buildTextField($this->t('Position'));
      }
      if ($jobProfile?->getEmployer() || $canSearchAll) {
        $form['job']['employer'] = $this->buildTextField($this->t('Employer'));
      }
      if ($jobProfile?->getIndustry() || $canSearchAll) {
        $form['job']['industry'] = $this->buildTextField($this->t('Industry'));
      }
    }

    if ($relationshipProfile || $canSearchAll) {
      $form['relationship'] = $this->buildDefaultContainer($this->t('Relationship'));
      if ($relationshipProfile?->getRelationshipStatus() || $canSearchAll) {
        $form['relationship']['relationship_status'] = $this->buildSelectField($this->t('Relationship status'), RelationshipStatus::translatableFormOptions(), useChosen: TRUE, multi: TRUE);
      }
      if ($relationshipProfile?->getRelationshipType() || $canSearchAll) {
        $form['relationship']['relationship_type'] = $this->buildSelectField($this->t('Relationship type'), RelationshipType::translatableFormOptions(), useChosen: TRUE, multi: TRUE);
      }
      if ($relationshipProfile?->getSexuality() || $canSearchAll) {
        $form['relationship']['sexuality'] = $this->buildSelectField($this->t('Sexuality'), Sexuality::translatableFormOptions(), useChosen: TRUE, multi: TRUE);
      }
    }

    if ($socialMediaProfile || $canSearchAll) {
      $form['social'] = $this->buildDefaultContainer($this->t('Social Media'));
      if ($socialMediaProfile?->getTwitter() || $canSearchAll) {
        $form['social']['twitter'] = $this->buildTextField($this->t('Twitter'));
      }
      if ($socialMediaProfile?->getInstagram() || $canSearchAll) {
        $form['social']['instagram'] = $this->buildTextField($this->t('Instagram'));
      }
      if ($socialMediaProfile?->getTwitch() || $canSearchAll) {
        $form['social']['twitch'] = $this->buildTextField($this->t('Twitch'));
      }
      if ($socialMediaProfile?->getDiscord() || $canSearchAll) {
        $form['social']['discord'] = $this->buildTextField($this->t('Discord'));
      }
      if ($socialMediaProfile?->getFacebook() || $canSearchAll) {
        $form['social']['facebook'] = $this->buildTextField($this->t('Facebook'));
      }
      if ($socialMediaProfile?->getLinkedin() || $canSearchAll) {
        $form['social']['linkedin'] = $this->buildTextField($this->t('LinkedIn'));
      }
      if ($socialMediaProfile?->getXing() || $canSearchAll) {
        $form['social']['xing'] = $this->buildTextField($this->t('Xing'));
      }
      if ($socialMediaProfile?->getPinterest() || $canSearchAll) {
        $form['social']['pinterest'] = $this->buildTextField($this->t('Pinterest'));
      }
      if ($socialMediaProfile?->getBehance() || $canSearchAll) {
        $form['social']['behance'] = $this->buildTextField($this->t('Behance'));
      }
      if ($socialMediaProfile?->getDribbble() || $canSearchAll) {
        $form['social']['dribbble'] = $this->buildTextField($this->t('dribbble'));
      }
    }

    $form['operator'] = $this->buildQueryOperatorField();

    $form['hidden_note'] = [
      '#type' => 'markup',
      '#markup' => $this->t("Some fields may be hidden because you don't have them filled in your own profile.") . '<br />',
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Search'),
    ];

    $form['results'] = [
      '#type' => 'markup',
      '#theme' => 'profile_card_list',
    ];

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $form_state->setRebuild(TRUE);
    $values = $form_state->getValues();
    $profile = Account::forActive()->getActiveProfile();

    $joinBaseProfile = FALSE;
    $joinCodingProfile = FALSE;
    $joinGamingProfile = FALSE;
    $joinJobProfile = FALSE;
    $joinRelationshipProfile = FALSE;
    $joinSocialMediaProfile = FALSE;

    $database = \Drupal::database();
    $select = $database->select('ohano_user_profile', 'up');
    $select->fields('up', ['id']);
    $select->condition('up.id', $profile->getId(), '!=');

    foreach ($values['base'] as $key => $value) {
      if (!empty($value) && (!isset($value['options']) || !empty($value['options']))) {
        $joinBaseProfile = TRUE;
      }
    }

    foreach ($values['coding'] as $key => $value) {
      if (!empty($value) && (!isset($value['options']) || !empty($value['options']))) {
        $joinCodingProfile = TRUE;
      }
    }

    foreach ($values['gaming'] as $key => $value) {
      if (!empty($value) && (!isset($value['options']) || !empty($value['options']))) {
        $joinGamingProfile = TRUE;
      }
    }

    foreach ($values['job'] as $key => $value) {
      if (!empty($value) && (!isset($value['options']) || !empty($value['options']))) {
        $joinJobProfile = TRUE;
      }
    }

    foreach ($values['relationship'] as $key => $value) {
      if (!empty($value) && (!isset($value['options']) || !empty($value['options']))) {
        $joinRelationshipProfile = TRUE;
      }
    }

    foreach ($values['social'] as $key => $value) {
      if (!empty($value) && (!isset($value['options']) || !empty($value['options']))) {
        $joinSocialMediaProfile = TRUE;
      }
    }

    if ($joinBaseProfile) {
      $select->join('ohano_base_profile', 'bp', 'up.id = bp.profile');
    }

    if ($joinCodingProfile) {
      $select->join('ohano_coding_profile', 'cp', 'up.id = cp.profile');
    }

    if ($joinGamingProfile) {
      $select->join('ohano_gaming_profile', 'gp', 'up.id = gp.profile');
    }

    if ($joinJobProfile) {
      $select->join('ohano_job_profile', 'jp', 'up.id = jp.profile');
    }

    if ($joinRelationshipProfile) {
      $select->join('ohano_relationship_profile', 'rp', 'up.id = rp.profile');
    }

    if ($joinSocialMediaProfile) {
      $select->join('ohano_social_media_profile', 'sp', 'up.id = sp.profile');
    }

    $conjunction = $values['operator'];

    $condition = $conjunction == 'OR' ? $select->orConditionGroup() : $select->andConditionGroup();

    if (!empty($values['base']['profile_name'])) {
      $condition->condition('up.profile_name', "%{$values['base']['profile_name']}%", 'LIKE');
    }
    if (!empty($values['base']['real_name'])) {
      $condition->condition('bp.realname', "%{$values['base']['real_name']}%", 'LIKE');
    }
    if (!empty($values['base']['gender'])) {
      $condition->condition('bp.gender', $values['base']['gender']);
    }
    if (!empty($values['base']['city'])) {
      $condition->condition('bp.city', "%{$values['base']['city']}%", 'LIKE');
    }
    if (!empty($values['base']['province'])) {
      $condition->condition('bp.province', "%{$values['base']['province']}%", 'LIKE');
    }
    if (!empty($values['base']['country'])) {
      $condition->condition('bp.country', "%{$values['base']['country']}%", 'LIKE');
    }

    if (!empty($values['coding']['github'])) {
      $condition->condition('cp.github', "%{$values['coding']['github']}%", 'LIKE');
    }
    if (!empty($values['coding']['gitlab'])) {
      $condition->condition('cp.gitlab', "%{$values['coding']['gitlab']}%", 'LIKE');
    }
    if (!empty($values['coding']['bitbucket'])) {
      $condition->condition('cp.bitbucket', "%{$values['coding']['bitbucket']}%", 'LIKE');
    }
    if (!empty($values['coding']['programming_languages']['options'])) {
      $select->join('coding_profile__programming_languages', 'cp_languages', 'up.id = cp_languages.entity_id');
      $innerCondition = $values['coding']['programming_languages']['operator'] == 'OR' ? $condition->orConditionGroup() : $condition->andConditionGroup();
      foreach ($values['coding']['programming_languages']['options'] as $option) {
        $innerCondition->condition('cp_languages.programming_languages_target_id', $option);
      }

      if (!empty($innerCondition->conditions())) {
        $condition->condition($innerCondition);
      }
    }
    if (!empty($values['coding']['systems']['options'])) {
      $select->join('coding_profile__systems', 'cp_systems', 'up.id = cp_systems.entity_id');
      $innerCondition = $values['coding']['systems']['operator'] == 'OR' ? $condition->orConditionGroup() : $condition->andConditionGroup();
      foreach ($values['coding']['systems']['options'] as $option) {
        $innerCondition->condition('cp_systems.systems_target_id', $option);
      }

      if (!empty($innerCondition->conditions())) {
        $condition->condition($innerCondition);
      }
    }

    if (!empty($values['gaming']['minecraft_name'])) {
      $condition->condition('gp.minecraft_name', "%{$values['gaming']['minecraft_name']}%", 'LIKE');
    }
    if (!empty($values['gaming']['valorant'])) {
      $condition->condition('gp.valorant', "%{$values['gaming']['valorant']}%", 'LIKE');
    }
    if (!empty($values['gaming']['league_of_legends'])) {
      $condition->condition('gp.league_of_legends', "%{$values['gaming']['league_of_legends']}%", 'LIKE');
    }
    if (!empty($values['gaming']['battle_net'])) {
      $condition->condition('gp.battle_net', "%{$values['gaming']['battle_net']}%", 'LIKE');
    }
    if (!empty($values['gaming']['ubisoft_connect'])) {
      $condition->condition('gp.ubisoft_connect', "%{$values['gaming']['ubisoft_connect']}%", 'LIKE');
    }
    if (!empty($values['gaming']['steam'])) {
      $condition->condition('gp.steam', "%{$values['gaming']['steam']}%", 'LIKE');
    }
    if (!empty($values['gaming']['ea_origin'])) {
      $condition->condition('gp.ea_origin', "%{$values['gaming']['ea_origin']}%", 'LIKE');
    }
    if (!empty($values['gaming']['games']['options'])) {
      $select->join('gaming_profile__games', 'gp_games', 'up.id = gp_games.entity_id');
      $innerCondition = $values['gaming']['games']['operator'] == 'OR' ? $condition->orConditionGroup() : $condition->andConditionGroup();
      foreach ($values['gaming']['games']['options'] as $option) {
        $innerCondition->condition('gp_games.games_target_id', $option);
      }

      if (!empty($innerCondition->conditions())) {
        $condition->condition($innerCondition);
      }
    }
    if (!empty($values['gaming']['platforms']['options'])) {
      $select->join('gaming_profile__platforms', 'gp_platforms', 'up.id = gp_platforms.entity_id');
      $innerCondition = $values['gaming']['platforms']['operator'] == 'OR' ? $condition->orConditionGroup() : $condition->andConditionGroup();
      foreach ($values['gaming']['platforms']['options'] as $option) {
        $innerCondition->condition('gp_platforms.platforms_target_id', $option);
      }

      if (!empty($innerCondition->conditions())) {
        $condition->condition($innerCondition);
      }
    }

    if (!empty($values['job']['education_degree'])) {
      $condition->condition('jp.education_degree', $values['job']['education_degree']);
    }
    if (!empty($values['job']['employment_status'])) {
      $condition->condition('jp.employment_status', $values['job']['employment_status']);
    }
    if (!empty($values['job']['position'])) {
      $condition->condition('jp.position', "%{$values['job']['position']}%", 'LIKE');
    }
    if (!empty($values['job']['employer'])) {
      $condition->condition('jp.employer', "%{$values['job']['employer']}%", 'LIKE');
    }
    if (!empty($values['job']['industry'])) {
      $condition->condition('jp.industry', "%{$values['job']['industry']}%", 'LIKE');
    }

    if (!empty($values['relationship']['relationship_status'])) {
      $condition->condition('rp.relationship_status', $values['relationship']['relationship_status']);
    }
    if (!empty($values['relationship']['relationship_type'])) {
      $condition->condition('rp.relationship_type', $values['relationship']['relationship_type']);
    }
    if (!empty($values['relationship']['sexuality'])) {
      $condition->condition('rp.sexuality', $values['relationship']['sexuality']);
    }

    if (!empty($values['social']['twitter'])) {
      $condition->condition('sp.twitter', "%{$values['social']['twitter']}%", 'LIKE');
    }
    if (!empty($values['social']['instagram'])) {
      $condition->condition('sp.instagram', "%{$values['social']['instagram']}%", 'LIKE');
    }
    if (!empty($values['social']['twitch'])) {
      $condition->condition('sp.twitch', "%{$values['social']['twitch']}%", 'LIKE');
    }
    if (!empty($values['social']['discord'])) {
      $condition->condition('sp.discord', "%{$values['social']['discord']}%", 'LIKE');
    }
    if (!empty($values['social']['facebook'])) {
      $condition->condition('sp.facebook', "%{$values['social']['facebook']}%", 'LIKE');
    }
    if (!empty($values['social']['linkedin'])) {
      $condition->condition('sp.linkedin', "%{$values['social']['linkedin']}%", 'LIKE');
    }
    if (!empty($values['social']['xing'])) {
      $condition->condition('sp.xing', "%{$values['social']['xing']}%", 'LIKE');
    }
    if (!empty($values['social']['pinterest'])) {
      $condition->condition('sp.pinterest', "%{$values['social']['pinterest']}%", 'LIKE');
    }
    if (!empty($values['social']['behance'])) {
      $condition->condition('sp.behance', "%{$values['social']['behance']}%", 'LIKE');
    }
    if (!empty($values['social']['dribbble'])) {
      $condition->condition('sp.dribbble', "%{$values['social']['dribbble']}%", 'LIKE');
    }

    if (count($condition->conditions()) > 1) {
      $select->condition($condition);
    }

    $executed = $select->execute();
    $res = $executed->fetchAll();
  }

}
