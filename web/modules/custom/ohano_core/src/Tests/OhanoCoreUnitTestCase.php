<?php

namespace Drupal\ohano_core\Tests;

use Drupal\Core\DependencyInjection\Container;
use Drupal\Tests\UnitTestCase;

/**
 * Test base class providing additional methods used by multiple classes.
 *
 * @package Drupal\ohano_core\Tests
 */
class OhanoCoreUnitTestCase extends UnitTestCase {

  /**
   * Builds the container used by Drupals dependency injection.
   *
   * @return \Drupal\Core\DependencyInjection\Container
   *   A DI container holding all needed services as mock objects.
   */
  protected function buildContainer(): Container {
    $services = [
      'plugin.manager.field.field_type' => '\Drupal\Core\Field\FieldTypePluginManager',
    ];
    $container = new Container();

    foreach ($services as $key => $class) {
      $classMock = $this->getMockBuilder($class)
        ->disableOriginalConstructor()
        ->getMock();

      switch ($key) {
        case 'plugin.manager.field.field_type':
          $classMock->method('getDefaultStorageSettings')->willReturn([]);
          $classMock->method('getDefaultFieldSettings')->willReturn([]);
          break;
      }

      $container->set($key, $classMock);

      unset($classMock);
    }

    return $container;
  }

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    $container = $this->buildContainer();
    \Drupal::setContainer($container);
  }

}
