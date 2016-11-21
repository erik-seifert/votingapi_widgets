<?php

namespace Drupal\votingapi_widgets;

/**
 * Implements lazy loading.
 */
class VotingApiLoader {

  /**
   * Build rate form.
   */
  public function buildForm($plugin_id, $entity_type, $entity_bundle, $entity_id, $vote_type, $field_name, $style, $show_results, $read_only) {
    $manager = \Drupal::service('plugin.manager.voting_api_widget.processor');
    $definitions = $manager->getDefinitions();
    $entity = \Drupal::service('entity_type.manager')->getStorage($entity_type)->load($entity_id);
    $plugin = $manager->createInstance($plugin_id, $definitions[$plugin_id]);
    $fieldDefinition = $entity->{$field_name}->getFieldDefinition();
    if ($fieldDefinition->get('status') != 1) {
      $read_only = TRUE;
    }
    return $plugin->buildForm($entity_type, $entity_bundle, $entity_id, $vote_type, $field_name, $style, $show_results, $read_only);
  }

}
