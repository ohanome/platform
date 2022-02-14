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
   * @return string
   *   The Minecraft name.
   */
  public function getMinecraftName(): string {
    return $this->get('minecraft_name')->value;
  }

  /**
   * Gets the Minecraft uuid.
   *
   * @return string
   *   The Minecraft uuid.
   */
  public function getMinecraftUuid(): string {
    return $this->get('minecraft_uuid')->value;
  }

  /**
   * Gets the VALORANT name.
   *
   * @return string
   *   The VALORANT name.
   */
  public function getValorant(): string {
    return $this->get('valorant')->value;
  }

  /**
   * Gets the League of Legends name.
   *
   * @return string
   *   The League of Legends name.
   */
  public function getLeagueOfLegends(): string {
    return $this->get('league_of_legends')->value;
  }

  /**
   * Gets the Battle.net name.
   *
   * @return string
   *   The Battle.net name.
   */
  public function getBattleNet(): string {
    return $this->get('battle_net')->value;
  }

  /**
   * Gets the Ubisoft Connect name.
   *
   * @return string
   *   The Ubisoft Connect name.
   */
  public function getUbisoftConnect(): string {
    return $this->get('ubisoft_connect')->value;
  }

  /**
   * Gets the Steam name.
   *
   * @return string
   *   The Steam name.
   */
  public function getSteam(): string {
    return $this->get('steam')->value;
  }

  /**
   * Gets the EA Origin name.
   *
   * @return string
   *   The EA Origin name.
   */
  public function getEaOrigin(): string {
    return $this->get('ea_origin')->value;
  }

  /**
   * Gets the games.
   *
   * @return \Drupal\taxonomy\Entity\Term[]
   *   The games.
   */
  public function getGames(): array {
    return $this->get('games')->referencedEntities();
  }

  /**
   * Gets the platforms the user plays on.
   *
   * @return \Drupal\taxonomy\Entity\Term[]
   *   The platforms.
   */
  public function getPlatforms(): array {
    return $this->get('platforms')->referencedEntities();
  }

  /**
   * Sets the Minecraft name.
   *
   * @param string $minecraftName
   *   The minecraft name to set.
   *
   * @return \Drupal\ohano_profile\Entity\GamingProfile
   *   The active instance of this class.
   *
   * @noinspection PhpUnused
   */
  public function setMinecraftName(string $minecraftName): GamingProfile {
    $this->set('minecraft_name', $minecraftName);
    return $this;
  }

  /**
   * Sets the Minecraft uuid.
   *
   * @param string $minecraftUuid
   *   The minecraft uuid to set.
   *
   * @return \Drupal\ohano_profile\Entity\GamingProfile
   *   The active instance of this class.
   *
   * @noinspection PhpUnused
   */
  public function setMinecraftUuid(string $minecraftUuid): GamingProfile {
    $this->set('minecraft_uuid', $minecraftUuid);
    return $this;
  }

  /**
   * Sets the VALORANT name.
   *
   * @param string $valorant
   *   The VALORANT name to set.
   *
   * @return \Drupal\ohano_profile\Entity\GamingProfile
   *   The active instance of this class.
   *
   * @noinspection PhpUnused
   */
  public function setValorant(string $valorant): GamingProfile {
    $this->set('valorant', $valorant);
    return $this;
  }

  /**
   * Sets the League of Legends name.
   *
   * @param string $leagueOfLegends
   *   The League of Legends name to set.
   *
   * @return \Drupal\ohano_profile\Entity\GamingProfile
   *   The active instance of this class.
   *
   * @noinspection PhpUnused
   */
  public function setLeagueOfLegends(string $leagueOfLegends): GamingProfile {
    $this->set('league_of_legends', $leagueOfLegends);
    return $this;
  }

  /**
   * Sets the Battle.net name.
   *
   * @param string $battleNet
   *   The Battle.net name to set.
   *
   * @return \Drupal\ohano_profile\Entity\GamingProfile
   *   The active instance of this class.
   *
   * @noinspection PhpUnused
   */
  public function setBattleNet(string $battleNet): GamingProfile {
    $this->set('battle_net', $battleNet);
    return $this;
  }

  /**
   * Sets the Ubisoft Connect name.
   *
   * @param string $ubisoftConnect
   *   The Ubisoft Connect name to set.
   *
   * @return \Drupal\ohano_profile\Entity\GamingProfile
   *   The active instance of this class.
   *
   * @noinspection PhpUnused
   */
  public function setUbisoftConnect(string $ubisoftConnect): GamingProfile {
    $this->set('ubisoft_connect', $ubisoftConnect);
    return $this;
  }

  /**
   * Sets the Steam name.
   *
   * @param string $steam
   *   The Steam name to set.
   *
   * @return \Drupal\ohano_profile\Entity\GamingProfile
   *   The active instance of this class.
   *
   * @noinspection PhpUnused
   */
  public function setSteam(string $steam): GamingProfile {
    $this->set('steam', $steam);
    return $this;
  }

  /**
   * Sets the EA Origin name.
   *
   * @param string $origin
   *   The EA Origin name to set.
   *
   * @return \Drupal\ohano_profile\Entity\GamingProfile
   *   The active instance of this class.
   *
   * @noinspection PhpUnused
   */
  public function setEaOrigin(string $origin): GamingProfile {
    $this->set('ea_origin', $origin);
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

}
