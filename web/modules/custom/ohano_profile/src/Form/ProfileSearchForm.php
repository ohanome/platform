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
use Drupal\ohano_profile\Option\Gender;

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

    $showHiddenFieldNotice = FALSE;

    $form = [];

    $form['base'] = $this->buildDefaultContainer($this->t('Basic information'), TRUE);

    $form['base']['profile_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('User- / Profile name'),
    ];

    if ($baseProfile->getRealName()) {
      $form['base']['real_name'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Name'),
      ];
    }
    else {
      $showHiddenFieldNotice = TRUE;
    }

    if ($baseProfile->getGender()) {
      $form['base']['gender'] = [
        '#type' => 'select',
        '#title' => $this->t('Gender'),
        '#options' => [NULL => '-'] + Gender::translatableFormOptions(),
      ];
    }
    else {
      $showHiddenFieldNotice = TRUE;
    }

    if ($baseProfile->getCity()) {
      $form['base']['city'] = [
        '#type' => 'textfield',
        '#title' => $this->t("City"),
      ];
    }
    else {
      $showHiddenFieldNotice = TRUE;
    }

    if ($baseProfile->getProvince()) {
      $form['base']['province'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Sate or province'),
      ];
    }
    else {
      $showHiddenFieldNotice = TRUE;
    }

    if ($baseProfile->getCountry()) {
      $form['base']['country'] = [
        '#type' => 'textfield',
        '#title' => $this->t("Country"),
      ];
    }
    else {
      $showHiddenFieldNotice = TRUE;
    }

    if ($codingProfile) {
      $form['coding'] = $this->buildDefaultContainer($this->t("Coding"), FALSE);
      if ($codingProfile->getGithub()) {
        $form['coding']['github'] = [
          '#type' => 'textfield',
          '#title' => $this->t("GitHub"),
        ];
      }
      else {
        $showHiddenFieldNotice = TRUE;
      }

      if ($codingProfile->getGitlab()) {
        $form['coding']['gitlab'] = [
          '#type' => 'textfield',
          '#title' => $this->t("GitLab"),
        ];
      }
      else {
        $showHiddenFieldNotice = TRUE;
      }

      if ($codingProfile->getBitbucket()) {
        $form['coding']['bitbucket'] = [
          '#type' => 'textfield',
          '#title' => $this->t("Bitbucket"),
        ];
      }
      else {
        $showHiddenFieldNotice = TRUE;
      }
    }
    else {
      $showHiddenFieldNotice = TRUE;
    }

    if ($showHiddenFieldNotice) {
      $this->messenger()->addWarning($this->t("Some fields are hidden because you don't have them filled in your own profile."));
    }

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Search'),
    ];

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $form_state->setRebuild(TRUE);
    $values = $form_state->getValues();
    $profile = Account::forActive()->getActiveProfile();

    $baseProfileJoined = FALSE;
    $codingProfileJoined = FALSE;
    $gamingProfileJoined = FALSE;
    $jobProfileJoined = FALSE;
    $relationshipProfileJoined = FALSE;
    $socialMediaProfileJoined = FALSE;

    $database = \Drupal::database();
    $select = $database->select('ohano_user_profile', 'up');
    $select->fields('up', ['id']);
    $select->condition('up.id', $profile->getId(), '!=');
    if (!empty($values['base']['profile_name'])) {
      $select->condition('up.profile_name', $values['base']['profile_name'], 'LIKE');
    }

    if (!empty($values['base']['real_name'])) {
      $select->join('ohano_base_profile', 'bp', 'up.id = bp.profile');
      $baseProfileJoined = TRUE;
      $select->condition('bp.realname', $values['base']['real_name'], 'LIKE');
    }

    if (!empty($values['base']['gender'])) {
      if (!$baseProfileJoined) {
        $select->join('ohano_base_profile', 'bp', 'up.id = bp.profile');
        $baseProfileJoined = TRUE;
      }
      $select->condition('bp.gender', $values['base']['gender']);
    }

    if (!empty($values['base']['city'])) {
      if (!$baseProfileJoined) {
        $select->join('ohano_base_profile', 'bp', 'up.id = bp.profile');
        $baseProfileJoined = TRUE;
      }
      $select->condition('bp.city', $values['base']['city'], 'LIKE');
    }

    if (!empty($values['base']['province'])) {
      if (!$baseProfileJoined) {
        $select->join('ohano_base_profile', 'bp', 'up.id = bp.profile');
        $baseProfileJoined = TRUE;
      }
      $select->condition('bp.province', $values['base']['province'], 'LIKE');
    }

    if (!empty($values['base']['country'])) {
      if (!$baseProfileJoined) {
        $select->join('ohano_base_profile', 'bp', 'up.id = bp.profile');
        $baseProfileJoined = TRUE;
      }
      $select->condition('bp.country', $values['base']['country'], 'LIKE');
    }

    if (!empty($values['coding']['github'])) {
      if (!$codingProfileJoined) {
        $select->join('ohano_coding_profile', 'cp', 'up.id = cp.profile');
        $codingProfileJoined = TRUE;
      }
      $select->condition('cp.github', $values['coding']['github'], 'LIKE');
    }

    if (!empty($values['coding']['gitlab'])) {
      if (!$codingProfileJoined) {
        $select->join('ohano_coding_profile', 'cp', 'up.id = cp.profile');
        $codingProfileJoined = TRUE;
      }
      $select->condition('cp.gitlab', $values['coding']['gitlab'], 'LIKE');
    }

    if (!empty($values['coding']['bitbucket'])) {
      if (!$codingProfileJoined) {
        $select->join('ohano_coding_profile', 'cp', 'up.id = cp.profile');
        $codingProfileJoined = TRUE;
      }
      $select->condition('cp.bitbucket', $values['coding']['bitbucket'], 'LIKE');
    }

    $executed = $select->execute();
    $res = $executed->fetchAll();
    dd($res);
  }

}
