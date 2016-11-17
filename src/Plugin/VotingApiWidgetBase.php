<?php

namespace Drupal\votingapi_widgets\Plugin;

use Drupal\Component\Plugin\PluginBase;

/**
 * Base class for Voting api widget plugins.
 */
abstract class VotingApiWidgetBase extends PluginBase implements VotingApiWidgetInterface {

  /**
   * Return label.
   */
  public function getLabel() {
    return $this->label;
  }

  /**
   * Return minimal value.
   */
  public function getValues() {
    return $this->getPluginDefinition()['values'];
  }

}
