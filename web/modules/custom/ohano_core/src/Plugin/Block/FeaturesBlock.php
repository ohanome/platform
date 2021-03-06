<?php

namespace Drupal\ohano_core\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\ohano_core\Feature\FeatureInterface;

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

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    $features = [];
    \Drupal::moduleHandler()->alter('features', $features);

    $toRender = [];
    foreach ($features as $feature) {
      if (!class_implements($feature, FeatureInterface::class)) {
        $warning = $this->t('Feature @feature does not implement @interface.', [
          '@feature' => $feature,
          '@interface' => FeatureInterface::class,
        ]);
        \Drupal::logger('ohano_core')->warning($warning);
        \Drupal::messenger()->addWarning($warning);
        continue;
      }

      /** @var \Drupal\ohano_core\Feature\FeatureInterface $feature */
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
        $renderable['#path'] = $path->toString();
      }
      if ($weight = $feature::getWeight()) {
        $renderable['#weight'] = $weight;
      }
      else {
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
