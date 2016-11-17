<?php

namespace Drupal\votingapi_widgets;

use Drupal\votingapi_widgets\Plugin\VotingApiWidgetManagerInterface;
use Drupal\Core\Form\FormBuilder;

/**
 * Implements lazy loading.
 */
class VotingApiLoader {

  /**
   * Build rate form.
   */
  public function buildForm($plugin_id, $entity_type, $entity_id, $vote_type, $field_name, $result_function, $style, $show_results, $read_only) {
    $manager = \Drupal::service('plugin.manager.voting_api_widget.processor');
    $definitions = $manager->getDefinitions();
    $entity = \Drupal::service('entity_type.manager')->getStorage($entity_type)->load($entity_id);
    $plugin = $manager->createInstance($plugin_id, $definitions[$plugin_id]);
    if ($read_only != FALSE && $entity->{$field_name} && $fieldDefinition = $entity->{$field_name}->getFieldDefinition()) {
      $read_only = $fieldDefinition->get('status');
      if (!$read_only) {
        $read_only = FALSE;
      }
    }
    return $plugin->buildForm($entity_type, $entity_id, $vote_type, $field_name, $result_function, $style, $show_results, $read_only);
  }

}
