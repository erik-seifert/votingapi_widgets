<?php

namespace Drupal\votingapi_widgets;

use Drupal\votingapi_widgets\Plugin\VotingApiWidgetManager;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Implements lazy loading.
 */
class VotingApiLoader {

  protected $manager;
  protected $entityTypeManager;

  public function __construct(VotingApiWidgetManager $manager, EntityTypeManagerInterface $entity_type_manager) {
    $this->manager = $manager;
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * Build rate form.
   */
  public function buildForm($plugin_id, $entity_type, $entity_bundle, $entity_id, $vote_type, $field_name, $style, $show_results, $read_only, $show_own_vote) {
    $definitions = $this->manager->getDefinitions();
    $entity = $this->entityTypeManager->getStorage($entity_type)->load($entity_id);
    $plugin = $this->manager->createInstance($plugin_id, $definitions[$plugin_id]);
    $fieldDefinition = $entity->{$field_name}->getFieldDefinition();
    if ($fieldDefinition->get('status') != 1) {
      $read_only = TRUE;
    }
    return $plugin->buildForm($entity_type, $entity_bundle, $entity_id, $vote_type, $field_name, $style, $show_results, $read_only, $show_own_vote);
  }

}
