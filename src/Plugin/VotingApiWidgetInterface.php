<?php

namespace Drupal\votingapi_widgets\Plugin;

use Drupal\Component\Plugin\PluginInspectionInterface;

/**
 * Defines an interface for Voting api widget plugins.
 */
interface VotingApiWidgetInterface extends PluginInspectionInterface {

  /**
   * Build form.
   */
  public function buildForm($entity_type, $entity_bundle, $entity_id, $vote_type, $field_name, $style, $show_results, $read_only = FALSE);

  /**
   * Get available styles.
   */
  public function getStyles();

}
