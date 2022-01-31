<?php

namespace Drupal\Tests\ohano_core\Unit;

use Drupal\ohano_core\OhanoCore;
use Drupal\ohano_core\Tests\OhanoCoreUnitTestCase;

/**
 * Tests the class OhanoCore.
 *
 * @package Drupal\Tests\ohano_core\Unit
 * @covers \Drupal\ohano_core\OhanoCore
 */
class OhanoCoreTest extends OhanoCoreUnitTestCase {

  /**
   * Tests the method createEntityDefaultFields().
   *
   * @covers \Drupal\ohano_core\OhanoCore::createEntityDefaultFields
   */
  public function testCreateEntityDefaultFields() {
    $fieldsArray = [];

    OhanoCore::createEntityDefaultFields($fieldsArray);

    $this->assertArrayHasKey('id', $fieldsArray);
    $this->assertArrayHasKey('uuid', $fieldsArray);
    $this->assertArrayHasKey('created', $fieldsArray);
    $this->assertArrayHasKey('updated', $fieldsArray);
  }

}
