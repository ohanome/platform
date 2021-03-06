<?php

namespace Drupal\ohano_profile\Entity;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\taxonomy\Entity\Term;

/**
 * Defines the GamingProfile entity.
 *
 * @package Drupal\ohano_profile\Entity\Profile
 *
 * @noinspection PhpUnused
 *
 * @ContentEntityType(
 *   id = "gaming_profile",
 *   label = @Translation("Gaming profile"),
 *   base_table = "ohano_gaming_profile",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *     "created" = "created",
 *     "updated" = "updated",
 *     "profile" = "profile",
 *     "minecraft_name" = "minecraft_name",
 *     "minecraft_uuid" = "minecraft_uuid",
 *     "valorant" = "valorant",
 *     "league_of_legends" = "league_of_legends",
 *     "battle_net" = "battle_net",
 *     "ubisoft_connect" = "ubisoft_connect",
 *     "steam" = "steam",
 *     "ea_origin" = "ea_origin",
 *     "games" = "games",
 *     "platforms" = "platforms",
 *   },
 *   handlers = {
 *     "storage_schema" = "Drupal\ohano_profile\Storage\Schema\SubProfileStorageSchema",
 *   }
 * )
 */
class GamingProfile extends SubProfileBase {

  const ENTITY_ID = 'gaming_profile';

  /**
   * {@inheritdoc}
   */
  public static function entityTypeId(): string {
    return self::ENTITY_ID;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['minecraft_name'] = BaseFieldDefinition::create('string');
    $fields['minecraft_uuid'] = BaseFieldDefinition::create('string');
    $fields['valorant'] = BaseFieldDefinition::create('string');
    $fields['league_of_legends'] = BaseFieldDefinition::create('string');
    $fields['battle_net'] = BaseFieldDefinition::create('string');
    $fields['ubisoft_connect'] = BaseFieldDefinition::create('string');
    $fields['steam'] = BaseFieldDefinition::create('string');
    $fields['ea_origin'] = BaseFieldDefinition::create('string');
    $fields['games'] = BaseFieldDefinition::create('entity_reference')
      ->setSetting('target_type', 'taxonomy_term')
      ->setSetting('handler', 'default')
      ->setSetting('handler_settings', [
        'target_bundles' => [
          'tags' => 'games',
        ],
      ])
      ->setCardinality(-1);
    $fields['platforms'] = BaseFieldDefinition::create('entity_reference')
      ->setSetting('target_type', 'taxonomy_term')
      ->setSetting('handler', 'default')
      ->setSetting('handler_settings', [
        'target_bundles' => [
          'tags' => 'gaming_platforms',
        ],
      ])
      ->setCardinality(-1);

    return $fields;
  }

  /**
   * Gets the Minecraft name.
   *
   * @return string|null
   *   The Minecraft name.
   */
  public function getMinecraftName(): ?string {
    return $this->get('minecraft_name')->value;
  }

  /**
   * Gets the Minecraft uuid.
   *
   * @return string|null
   *   The Minecraft uuid.
   */
  public function getMinecraftUuid(): ?string {
    return $this->get('minecraft_uuid')->value;
  }

  /**
   * Gets the VALORANT name.
   *
   * @return string|null
   *   The VALORANT name.
   */
  public function getValorant(): ?string {
    return $this->get('valorant')->value;
  }

  /**
   * Gets the League of Legends name.
   *
   * @return string|null
   *   The League of Legends name.
   */
  public function getLeagueOfLegends(): ?string {
    return $this->get('league_of_legends')->value;
  }

  /**
   * Gets the Battle.net name.
   *
   * @return string|null
   *   The Battle.net name.
   */
  public function getBattleNet(): ?string {
    return $this->get('battle_net')->value;
  }

  /**
   * Gets the Ubisoft Connect name.
   *
   * @return string|null
   *   The Ubisoft Connect name.
   */
  public function getUbisoftConnect(): ?string {
    return $this->get('ubisoft_connect')->value;
  }

  /**
   * Gets the Steam name.
   *
   * @return string|null
   *   The Steam name.
   */
  public function getSteam(): ?string {
    return $this->get('steam')->value;
  }

  /**
   * Gets the EA Origin name.
   *
   * @return string|null
   *   The EA Origin name.
   */
  public function getEaOrigin(): ?string {
    return $this->get('ea_origin')->value;
  }

  /**
   * Gets the games.
   *
   * @return \Drupal\taxonomy\Entity\Term[]|null
   *   The games.
   */
  public function getGames(): ?array {
    return $this->get('games')->referencedEntities();
  }

  /**
   * Gets the platforms the user plays on.
   *
   * @return \Drupal\taxonomy\Entity\Term[]|null
   *   The platforms.
   */
  public function getPlatforms(): ?array {
    return $this->get('platforms')->referencedEntities();
  }

  /**
   * Sets the Minecraft name.
   *
   * @param string|null $minecraftName
   *   The minecraft name to set.
   *
   * @return \Drupal\ohano_profile\Entity\GamingProfile
   *   The active instance of this class.
   *
   * @noinspection PhpUnused
   */
  public function setMinecraftName(string $minecraftName = NULL): GamingProfile {
    $this->set('minecraft_name', $minecraftName);
    return $this;
  }

  /**
   * Sets the Minecraft uuid.
   *
   * @param string|null $minecraftUuid
   *   The minecraft uuid to set.
   *
   * @return \Drupal\ohano_profile\Entity\GamingProfile
   *   The active instance of this class.
   *
   * @noinspection PhpUnused
   */
  public function setMinecraftUuid(string $minecraftUuid = NULL): GamingProfile {
    $this->set('minecraft_uuid', $minecraftUuid);
    return $this;
  }

  /**
   * Sets the VALORANT name.
   *
   * @param string|null $valorant
   *   The VALORANT name to set.
   *
   * @return \Drupal\ohano_profile\Entity\GamingProfile
   *   The active instance of this class.
   *
   * @noinspection PhpUnused
   */
  public function setValorant(string $valorant = NULL): GamingProfile {
    $this->set('valorant', $valorant);
    return $this;
  }

  /**
   * Sets the League of Legends name.
   *
   * @param string|null $leagueOfLegends
   *   The League of Legends name to set.
   *
   * @return \Drupal\ohano_profile\Entity\GamingProfile
   *   The active instance of this class.
   *
   * @noinspection PhpUnused
   */
  public function setLeagueOfLegends(string $leagueOfLegends = NULL): GamingProfile {
    $this->set('league_of_legends', $leagueOfLegends);
    return $this;
  }

  /**
   * Sets the Battle.net name.
   *
   * @param string|null $battleNet
   *   The Battle.net name to set.
   *
   * @return \Drupal\ohano_profile\Entity\GamingProfile
   *   The active instance of this class.
   *
   * @noinspection PhpUnused
   */
  public function setBattleNet(string $battleNet = NULL): GamingProfile {
    $this->set('battle_net', $battleNet);
    return $this;
  }

  /**
   * Sets the Ubisoft Connect name.
   *
   * @param string|null $ubisoftConnect
   *   The Ubisoft Connect name to set.
   *
   * @return \Drupal\ohano_profile\Entity\GamingProfile
   *   The active instance of this class.
   *
   * @noinspection PhpUnused
   */
  public function setUbisoftConnect(string $ubisoftConnect = NULL): GamingProfile {
    $this->set('ubisoft_connect', $ubisoftConnect);
    return $this;
  }

  /**
   * Sets the Steam name.
   *
   * @param string|null $steam
   *   The Steam name to set.
   *
   * @return \Drupal\ohano_profile\Entity\GamingProfile
   *   The active instance of this class.
   *
   * @noinspection PhpUnused
   */
  public function setSteam(string $steam = NULL): GamingProfile {
    $this->set('steam', $steam);
    return $this;
  }

  /**
   * Sets the EA Origin name.
   *
   * @param string|null $origin
   *   The EA Origin name to set.
   *
   * @return \Drupal\ohano_profile\Entity\GamingProfile
   *   The active instance of this class.
   *
   * @noinspection PhpUnused
   */
  public function setEaOrigin(string $origin = NULL): GamingProfile {
    $this->set('ea_origin', $origin);
    return $this;
  }

  /**
   * Sets the games.
   *
   * @param \Drupal\taxonomy\Entity\Term[]|array[]|null $games
   *   The games to set.
   *
   * @return \Drupal\ohano_profile\Entity\GamingProfile
   *   The active instance of this class.
   *
   * @noinspection PhpUnused
   */
  public function setGames(array $games = NULL): GamingProfile {
    $this->setTermValues('games', $games);
    return $this;
  }

  /**
   * Sets the platforms.
   *
   * @param \Drupal\taxonomy\Entity\Term[]|array[]|null $platforms
   *   The platforms to set.
   *
   * @return \Drupal\ohano_profile\Entity\GamingProfile
   *   The active instance of this class.
   *
   * @noinspection PhpUnused
   */
  public function setPlatforms(array $platforms = NULL): GamingProfile {
    $this->setTermValues('platforms', $platforms);
    return $this;
  }

  /**
   * Adds the given game to the list of games.
   *
   * @param \Drupal\taxonomy\Entity\Term $game
   *   The game to add.
   *
   * @return \Drupal\ohano_profile\Entity\GamingProfile
   *   The active instance of this class.
   *
   * @noinspection PhpUnused
   */
  public function addGame(Term $game): GamingProfile {
    foreach ($this->getGames() as $savedGame) {
      if ($game->id() == $savedGame->id()) {
        return $this;
      }
    }

    $this->get('games')->appendItem($game);
    return $this;
  }

  /**
   * Removes the given game from the list of games.
   *
   * @param \Drupal\taxonomy\Entity\Term $game
   *   The game to remove.
   *
   * @return \Drupal\ohano_profile\Entity\GamingProfile
   *   The active instance of this class.
   *
   * @noinspection PhpUnused
   */
  public function removeGame(Term $game): GamingProfile {
    // Find the index.
    $foundIndex = NULL;
    foreach ($this->getGames() as $index => $term) {
      if ($term->id() == $game->id()) {
        $foundIndex = $index;
      }
    }

    if ($foundIndex != NULL) {
      $this->get('games')->removeItem($foundIndex);
    }

    return $this;
  }

  /**
   * Adds the given platform to the list of platforms.
   *
   * @param \Drupal\taxonomy\Entity\Term $platform
   *   The platform to add.
   *
   * @return \Drupal\ohano_profile\Entity\GamingProfile
   *   The active instance of this class.
   *
   * @noinspection PhpUnused
   */
  public function addPlatform(Term $platform): GamingProfile {
    foreach ($this->getPlatforms() as $savedPlatform) {
      if ($platform->id() == $savedPlatform->id()) {
        return $this;
      }
    }

    $this->get('platforms')->appendItem($platform);
    return $this;
  }

  /**
   * Removes the given platform from the list of platforms.
   *
   * @param \Drupal\taxonomy\Entity\Term $platform
   *   The platform to remove.
   *
   * @return \Drupal\ohano_profile\Entity\GamingProfile
   *   The active instance of this class.
   *
   * @noinspection PhpUnused
   */
  public function removePlatform(Term $platform): GamingProfile {
    // Find the index.
    $foundIndex = NULL;
    foreach ($this->getPlatforms() as $index => $term) {
      if ($term->id() == $platform->id()) {
        $foundIndex = $index;
      }
    }

    if ($foundIndex != NULL) {
      $this->get('platforms')->removeItem($foundIndex);
    }

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function render(): array {
    /** @var \Drupal\ohano_core\Service\RenderService $renderService */
    $renderService = \Drupal::service('ohano_core.render');

    $games = $this->getGames();
    $renderedGames = [];
    if (!empty($games)) {
      foreach ($games as $game) {
        $renderedGames[] = $renderService->renderTerm($game);
      }
    }

    $platforms = $this->getPlatforms();
    $renderedPlatforms = [];
    if (!empty($platforms)) {
      foreach ($platforms as $platform) {
        $renderedPlatforms[] = $renderService->renderTerm($platform);
      }
    }

    return parent::render() + [
      'minecraft_name' => $this->getMinecraftName(),
      'minecraft_uuid' => $this->getMinecraftUuid(),
      'valorant' => $this->getValorant(),
      'league_of_legends' => $this->getLeagueOfLegends(),
      'battle_net' => $this->getBattleNet(),
      'ubisoft_connect' => $this->getUbisoftConnect(),
      'steam' => $this->getSteam(),
      'ea_origin' => $this->getEaOrigin(),
      'games' => $renderedGames,
      'platforms' => $renderedPlatforms,
    ];
  }

  /**
   * Renders the gaming profile form.
   *
   * @param \Drupal\ohano_profile\Entity\SubProfileBase $subProfile
   *   The sub profile to render the form for.
   *
   * @return array
   *   The render array for the gaming profile form.
   *
   * @throws \Exception
   */
  public static function renderForm(SubProfileBase $subProfile): array {
    if (!$subProfile instanceof GamingProfile) {
      throw new \Exception('Parameter must be of type GamingProfile');
    }
    /** @var \Drupal\ohano_profile\Entity\GamingProfile $subProfile */

    $form = [];

    $form['minecraft_name'] = [
      '#type' => 'textfield',
      '#title' => t('Minecraft name'),
      '#default_value' => $subProfile->getMinecraftName(),
    ];

    $form['minecraft_uuid'] = [
      '#type' => 'textfield',
      '#title' => t('Minecraft UUID'),
      '#description' => t('Your Minecraft UUID will be fetched based on your username.'),
      '#default_value' => $subProfile->getMinecraftUuid(),
      '#attributes' => [
        'disabled' => [
          'disabled',
        ],
      ],
    ];

    $form['valorant'] = [
      '#type' => 'textfield',
      '#title' => t('VALORANT'),
      '#default_value' => $subProfile->getValorant(),
    ];

    $form['league_of_legends'] = [
      '#type' => 'textfield',
      '#title' => t('League of Legends'),
      '#default_value' => $subProfile->getLeagueOfLegends(),
    ];

    $form['battle_net'] = [
      '#type' => 'textfield',
      '#title' => t('Battle.net'),
      '#default_value' => $subProfile->getBattleNet(),
    ];

    $form['ubisoft_connect'] = [
      '#type' => 'textfield',
      '#title' => t('Ubisoft Connect'),
      '#default_value' => $subProfile->getUbisoftConnect(),
    ];

    $form['steam'] = [
      '#type' => 'textfield',
      '#title' => t('Steam'),
      '#default_value' => $subProfile->getSteam(),
    ];

    $form['ea_origin'] = [
      '#type' => 'textfield',
      '#title' => t('EA Origin'),
      '#default_value' => $subProfile->getEaOrigin(),
    ];

    $allTerms = \Drupal::entityQuery('taxonomy_term')
      ->condition('vid', 'games')
      ->execute();
    $allTerms = Term::loadMultiple($allTerms);
    foreach ($allTerms as $tid => $term) {
      $allTerms[$tid] = $term->getName();
    }

    $selectedTerms = $subProfile->getGames();
    foreach ($selectedTerms as $index => $term) {
      $selectedTerms[$index] = $term->id();
    }

    $form['games'] = [
      '#type' => 'select',
      '#multiple' => TRUE,
      '#title' => t('Games'),
      '#description' => t('What games do you play?'),
      '#options' => $allTerms,
      '#default_value' => $selectedTerms,
      '#chosen' => TRUE,
    ];

    $allTerms = \Drupal::entityQuery('taxonomy_term')
      ->condition('vid', 'gaming_platforms')
      ->execute();
    $allTerms = Term::loadMultiple($allTerms);
    foreach ($allTerms as $tid => $term) {
      $allTerms[$tid] = $term->getName();
    }

    $selectedTerms = $subProfile->getPlatforms();
    foreach ($selectedTerms as $index => $term) {
      $selectedTerms[$index] = $term->id();
    }

    $form['platforms'] = [
      '#type' => 'select',
      '#multiple' => TRUE,
      '#title' => t('Gaming platforms'),
      '#options' => $allTerms,
      '#default_value' => $selectedTerms,
      '#chosen' => TRUE,
    ];

    return $form;
  }

}
