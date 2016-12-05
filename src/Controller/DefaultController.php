<?php

namespace Drupal\votingapi_widgets\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class DefaultController.
 *
 * @package Drupal\votingapi_widgets\Controller
 */
class DefaultController extends ControllerBase {

  /**
   * Hello.
   *
   * @return string
   *   Return Hello string.
   */
  public function hello() {
    return [
      '#type' => 'rate',
      '#plugin' => 'fivestar',
      '#allow_empty' => TRUE,
      '#show_results' => TRUE,
      '#default_value' => 3,
    ];
  }

}
