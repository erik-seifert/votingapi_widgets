<?php

namespace Drupal\votingapi_widgets\Tests;

use Drupal\simpletest\WebTestBase;

/**
 * Provides automated tests for the votingapi_widgets module.
 */
class DefaultControllerTest extends WebTestBase {


  /**
   * {@inheritdoc}
   */
  public static function getInfo() {
    return array(
      'name' => "votingapi_widgets DefaultController's controller functionality",
      'description' => 'Test Unit for module votingapi_widgets and controller DefaultController.',
      'group' => 'Other',
    );
  }

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();
  }

  /**
   * Tests votingapi_widgets functionality.
   */
  public function testDefaultController() {
    // Check that the basic functions of module votingapi_widgets.
    $this->assertEquals(TRUE, TRUE, 'Test Unit Generated via Drupal Console.');
  }

}
