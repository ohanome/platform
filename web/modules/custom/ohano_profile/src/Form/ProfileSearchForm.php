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

    $showHiddenFieldNotice = FALSE;

    $form = [];

    $form['to_simple'] = [
      '#type' => 'markup',
      '#markup' => '<a href="#">' . $this->t('To the simple search') . '</a><br />',
    ];

    $canSearchAll = \Drupal::currentUser()->hasPermission('ohano search user by all fields');

    $form['base'] = $this->buildDefaultContainer($this->t('Basic information'), TRUE);

    $form['base']['profile_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('User- / Profile name'),
    ];

    if ($baseProfile->getRealName() || $canSearchAll) {
      $form['base']['real_name'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Name'),
      ];
    }
    else {
      $showHiddenFieldNotice = TRUE;
    }

    if ($baseProfile->getGender() || $canSearchAll) {
      $form['base']['gender'] = [
        '#type' => 'select',
        '#chosen' => TRUE,
        '#multiple' => TRUE,
        '#title' => $this->t('Gender'),
        '#options' => Gender::translatableFormOptions(),
      ];
    }
    else {
      $showHiddenFieldNotice = TRUE;
    }

    if ($baseProfile->getCity() || $canSearchAll) {
      $form['base']['city'] = [
        '#type' => 'textfield',
        '#title' => $this->t("City"),
      ];
    }
    else {
      $showHiddenFieldNotice = TRUE;
    }

    if ($baseProfile->getProvince() || $canSearchAll) {
      $form['base']['province'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Sate or province'),
      ];
    }
    else {
      $showHiddenFieldNotice = TRUE;
    }

    if ($baseProfile->getCountry() || $canSearchAll) {
      $form['base']['country'] = [
        '#type' => 'textfield',
        '#title' => $this->t("Country"),
      ];
    }
    else {
      $showHiddenFieldNotice = TRUE;
    }

    if ($codingProfile || $canSearchAll) {
      $form['coding'] = $this->buildDefaultContainer($this->t("Coding"), TRUE);
      if ($codingProfile->getGithub() || $canSearchAll) {
        $form['coding']['github'] = [
          '#type' => 'textfield',
          '#title' => $this->t("GitHub"),
        ];
      }
      else {
        $showHiddenFieldNotice = TRUE;
      }

      if ($codingProfile->getGitlab() || $canSearchAll) {
        $form['coding']['gitlab'] = [
          '#type' => 'textfield',
          '#title' => $this->t("GitLab"),
        ];
      }
      else {
        $showHiddenFieldNotice = TRUE;
      }

      if ($codingProfile->getBitbucket() || $canSearchAll) {
        $form['coding']['bitbucket'] = [
          '#type' => 'textfield',
          '#title' => $this->t("Bitbucket"),
        ];
      }
      else {
        $showHiddenFieldNotice = TRUE;
      }

      if (empty($codingProfile->getProgrammingLanguages()) || $canSearchAll) {
        $programmingLanguages = \Drupal::entityTypeManager()
          ->getStorage('taxonomy_term')
          ->loadTree('programming_languages');
        $options = [];
        foreach ($programmingLanguages as $language) {
          $options[$language->tid] = $language->name;
        }

        $form['coding']['programming_languages'] = $this->buildDefaultContainer($this->t('Programming languages'), TRUE);
        $form['coding']['programming_languages']['languages'] = [
          '#type' => 'select',
          '#chosen' => TRUE,
          '#multiple' => TRUE,
          '#options' => $options,
          '#title' => $this->t('Programming languages'),
        ];

        $form['coding']['programming_languages']['operator'] = [
          '#type' => 'radios',
          '#title' => $this->t('Must include'),
          '#options' => [
            'AND' => $this->t('all'),
            'OR' => $this->t('one of the selected'),
          ],
          '#default_value' => 'OR',
        ];
      }
      else {
        $showHiddenFieldNotice = TRUE;
      }

      if (empty($codingProfile->getSystems()) || $canSearchAll) {
        $systems = \Drupal::entityTypeManager()
          ->getStorage('taxonomy_term')
          ->loadTree('systems');
        $options = [];
        foreach ($systems as $system) {
          $options[$system->tid] = $system->name;
        }

        $form['coding']['systems'] = $this->buildDefaultContainer($this->t('Systems'), TRUE);
        $form['coding']['systems']['systems'] = [
          '#type' => 'select',
          '#chosen' => TRUE,
          '#multiple' => TRUE,
          '#options' => $options,
          '#title' => $this->t('Systems'),
        ];

        $form['coding']['systems']['operator'] = [
          '#type' => 'radios',
          '#title' => $this->t('Must include'),
          '#options' => [
            'AND' => $this->t('all'),
            'OR' => $this->t('one of the selected'),
          ],
          '#default_value' => 'OR',
        ];
      }
      else {
        $showHiddenFieldNotice = TRUE;
      }
    }
    else {
      $showHiddenFieldNotice = TRUE;
    }

    if ($gamingProfile || $canSearchAll) {
      $form['gaming'] = $this->buildDefaultContainer($this->t('Gaming'));

      if ($gamingProfile->getMinecraftName() || $canSearchAll) {
        $form['gaming']['minecraft_name'] = [
          '#type' => 'textfield',
          '#title' => $this->t('Minecraft name'),
        ];
      }
      else {
        $showHiddenFieldNotice = TRUE;
      }

      if ($gamingProfile->getValorant() || $canSearchAll) {
        $form['gaming']['valorant'] = [
          '#type' => 'textfield',
          '#title' => $this->t('VALORANT'),
        ];
      }
      else {
        $showHiddenFieldNotice = TRUE;
      }

      if ($gamingProfile->getLeagueOfLegends() || $canSearchAll) {
        $form['gaming']['league_of_legends'] = [
          '#type' => 'textfield',
          '#title' => $this->t('League of Legends'),
        ];
      }
      else {
        $showHiddenFieldNotice = TRUE;
      }

      if ($gamingProfile->getBattleNet() || $canSearchAll) {
        $form['gaming']['battle_net'] = [
          '#type' => 'textfield',
          '#title' => $this->t('Battle.net'),
        ];
      }
      else {
        $showHiddenFieldNotice = TRUE;
      }

      if ($gamingProfile->getUbisoftConnect() || $canSearchAll) {
        $form['gaming']['ubisoft_connect'] = [
          '#type' => 'textfield',
          '#title' => $this->t('Ubisoft Connect'),
        ];
      }
      else {
        $showHiddenFieldNotice = TRUE;
      }

      if ($gamingProfile->getSteam() || $canSearchAll) {
        $form['gaming']['steam'] = [
          '#type' => 'textfield',
          '#title' => $this->t('Steam'),
        ];
      }
      else {
        $showHiddenFieldNotice = TRUE;
      }

      if ($gamingProfile->getEaOrigin() || $canSearchAll) {
        $form['gaming']['ea_origin'] = [
          '#type' => 'textfield',
          '#title' => $this->t('EA Origin'),
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

    $form['operator'] = [
      '#type' => 'radios',
      '#title' => $this->t('Must include'),
      '#options' => [
        'AND' => $this->t('all'),
        'OR' => $this->t('one of the selected'),
      ],
      '#default_value' => 'OR',
    ];

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
    $conjunction = $values['operator'];

    $condition = $select->conditionGroupFactory($conjunction);

    if (!empty($values['base']['profile_name'])) {
      $condition->condition('up.profile_name', $values['base']['profile_name'], 'LIKE');
    }

    if (!empty($values['base']['real_name'])) {
      $select->join('ohano_base_profile', 'bp', 'up.id = bp.profile');
      $baseProfileJoined = TRUE;
      $condition->condition('bp.realname', $values['base']['real_name'], 'LIKE');
    }

    if (!empty($values['base']['gender'])) {
      if (!$baseProfileJoined) {
        $select->join('ohano_base_profile', 'bp', 'up.id = bp.profile');
        $baseProfileJoined = TRUE;
      }
      $condition->condition('bp.gender', $values['base']['gender']);
    }

    if (!empty($values['base']['city'])) {
      if (!$baseProfileJoined) {
        $select->join('ohano_base_profile', 'bp', 'up.id = bp.profile');
        $baseProfileJoined = TRUE;
      }
      $condition->condition('bp.city', $values['base']['city'], 'LIKE');
    }

    if (!empty($values['base']['province'])) {
      if (!$baseProfileJoined) {
        $select->join('ohano_base_profile', 'bp', 'up.id = bp.profile');
        $baseProfileJoined = TRUE;
      }
      $condition->condition('bp.province', $values['base']['province'], 'LIKE');
    }

    if (!empty($values['base']['country'])) {
      if (!$baseProfileJoined) {
        $select->join('ohano_base_profile', 'bp', 'up.id = bp.profile');
        $baseProfileJoined = TRUE;
      }
      $condition->condition('bp.country', $values['base']['country'], 'LIKE');
    }

    if (!empty($values['coding']['github'])) {
      if (!$codingProfileJoined) {
        $select->join('ohano_coding_profile', 'cp', 'up.id = cp.profile');
        $codingProfileJoined = TRUE;
      }
      $condition->condition('cp.github', $values['coding']['github'], 'LIKE');
    }

    if (!empty($values['coding']['gitlab'])) {
      if (!$codingProfileJoined) {
        $select->join('ohano_coding_profile', 'cp', 'up.id = cp.profile');
        $codingProfileJoined = TRUE;
      }
      $condition->condition('cp.gitlab', $values['coding']['gitlab'], 'LIKE');
    }

    if (!empty($values['coding']['bitbucket'])) {
      if (!$codingProfileJoined) {
        $select->join('ohano_coding_profile', 'cp', 'up.id = cp.profile');
        $codingProfileJoined = TRUE;
      }
      $condition->condition('cp.bitbucket', $values['coding']['bitbucket'], 'LIKE');
    }

    $select->condition($condition);

    $executed = $select->execute();
    $res = $executed->fetchAll();
    dd($res);
  }

}
