<?php

namespace Drupal\votingapi_widgets\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\Core\Config\ConfigCrudEvent;
use Drupal\Core\Config\ConfigEvents;
use Drupal\field\Entity\FieldConfig;

/**
 * Subscribe to KernelEvents::REQUEST events and redirect if site is currently
 * in maintenance mode.
 */
class FieldConfigEventSubscriber implements EventSubscriberInterface {

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[ConfigEvents::DELETE][] = array('checkForFieldConfig');
    return $events;
  }

  /**
   * Check for field config.
   *
   * @param GetResponseEvent $event
   */
  public function checkForFieldConfig(ConfigCrudEvent $event) {
    // If system maintenance mode is enabled, redirect to a different domain.
    $configName = explode('.', $event->getConfig()->getName());
    $config = $event->getConfig();

    if ($configName[0] != 'field' && $configName[1] != 'field') {
      return;
    }
    // 
    // dsm($config->getName());
    // dsm($config);
    //
    // dsm($event->getConfig()->get('field_type'));
    $fieldBundle = $configName[2];
    $fieldName = $configName[3];

    // $config = FieldConfig::loadByName()
    // dsm($event->getConfig()->getName());
    // dsm($event->getConfig());
  }

}
