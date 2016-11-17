<?php

namespace Drupal\votingapi_widgets\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a Voting api widget item annotation object.
 *
 * @see \Drupal\votingapi_widgets\Plugin\VotingApiWidgetManager
 * @see plugin_api
 *
 * @Annotation
 */
class VotingApiWidget extends Plugin {

  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The minimal vote.
   *
   * @var min
   */
  public $values;

  /**
   * The label of the plugin.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $label;

}
