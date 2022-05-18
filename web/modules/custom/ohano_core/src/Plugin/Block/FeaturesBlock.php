<?php

namespace Drupal\ohano_core\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\ohano_core\Feature\FeatureInterface;
use ReflectionClass;

/**
 * Provides the feature block with links to the main platform features.
 *
 * @Block(
 *   id = "ohano_core__features_block",
 *   admin_label = @Translation("Features Block"),
 *   category = @Translation("ohano Core"),
 * )
 */
class FeaturesBlock extends BlockBase {

  public function build() {
    $features = [];
    \Drupal::moduleHandler()->alter('features', $features);

    $toRender = [];
    foreach ($features as $feature) {
      if (!class_implements($feature, FeatureInterface::class)) {
        $warning = $this->t('Feature ' . (string) $feature . ' does not implement ' . FeatureInterface::class);
        \Drupal::logger('ohano_core')->warning($warning);
        \Drupal::messenger()->addWarning($warning);
        continue;
      }

      /** @var FeatureInterface $feature */
      $renderable = [
        '#theme' => 'feature',
      ];
      if ($name = $feature::getName()) {
        $renderable['#name'] = $name;
      }
      if ($iconClass = $feature::getIconClass()) {
        $renderable['#icon_class'] = $iconClass;
      }
      if ($path = $feature::getPath()) {
        $renderable['#path'] = $path->getRouteName();
      }
      if ($weight = $feature::getWeight()) {
        $renderable['#weight'] = $weight;
      } else {
        $renderable['#weight'] = 0;
      }

      $toRender[] = $renderable;
    }

    usort($toRender, function ($a, $b) {
      return $a['#weight'] <=> $b['#weight'];
    });

    return [
      '#theme' => 'features_block',
      '#features' => $toRender,
      '#cache' => [
        'max-age' => 0,
      ],
    ];
  }

}
