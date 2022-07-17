<?php

namespace Drupal\ohano_core\Service;

use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Theme\ThemeNegotiatorInterface;

/**
 * Theme negotiator for ohano_core.
 *
 * @package Drupal\ohano_core\Service
 */
class ThemeNegotiator implements ThemeNegotiatorInterface {

  /**
   * {@inheritdoc}
   */
  public function applies(RouteMatchInterface $route_match): bool {
    return $route_match->getRouteName() === 'entity.node.canonical' && $route_match->getParameter('node')->getType() === 'landingpage';
  }

  /**
   * {@inheritdoc}
   */
  public function determineActiveTheme(RouteMatchInterface $route_match): ?string {
    return 'ohano_landingpage';
  }

}
