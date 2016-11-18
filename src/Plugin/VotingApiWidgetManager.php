<?php

namespace Drupal\votingapi_widgets\Plugin;

use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;

/**
 * Provides the Voting api widget plugin manager.
 */
class VotingApiWidgetManager extends DefaultPluginManager {

  /**
   * Constructor for VotingApiWidgetManager objects.
   *
   * @param \Traversable $namespaces
   *   An object that implements \Traversable which contains the root paths
   *   keyed by the corresponding namespace to look for plugin implementations.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   Cache backend instance to use.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler to invoke the alter hook with.
   */
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    parent::__construct('Plugin/votingapi_widget', $namespaces, $module_handler, 'Drupal\votingapi_widgets\Plugin\VotingApiWidgetInterface', 'Drupal\votingapi_widgets\Annotation\VotingApiWidget');
    $this->alterInfo('votingapi_widgets_voting_api_widget_info');
    $this->setCacheBackend($cache_backend, 'votingapi_widgets_voting_api_widget_plugins');
  }

}
