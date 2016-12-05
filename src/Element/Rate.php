<?php

namespace Drupal\votingapi_widgets\Element;

use Drupal\Core\Render\Element\Select;
use Drupal\Core\Render\Element;
use Drupal\Component\Serialization\Json;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides an example element.
 *
 * @FormElement("rate")
 */
class Rate extends Select {

  /**
   * {@inheritdoc}
   */
  public function getInfo() {
    $class = get_class($this);
    $info = parent::getInfo();
    $info['#plugin'] = FALSE;
    $info['#allow_empty'] = TRUE;
    $info['#readonly'] = FALSE;
    $info['#multiple'] = FALSE;

    $info['#vote'] = FALSE;

    $info['#settings'] = [
      ['show_results' => TRUE],
    ];

    $info['#process'] = [
      [$class, 'processAjaxForm'],
      [$class, 'processRateElement'],
    ];

    $info['#pre_render'] = [
      [$class, 'preRenderAjaxForm'],
      [$class, 'preRenderElement'],
    ];
    $info['#settings'] = [];
    // dsm($info);
    return $info;
  }

  /**
   * {@inheritdoc}
   */
  public function validateRate(&$element, FormStateInterface $form_state, &$complete_form) {
    $plugin = \Drupal::service('plugin.manager.voting_api_widget.processor')->createInstance($element['#plugin']);
    if (!$plugin) {
      return;
    }
    $values = array_keys($plugin->getValues());
    if ($element['#empty_value']) {
      $values[] = $element['#empty_value'];
    }
    return (in_array($element['#value'], $values));
  }

  /**
   * {@inheritdoc}
   */
  public static function processRateElement(&$element, FormStateInterface $form_state, &$complete_form) {
    parent::processSelect($element, $form_state, $complete_form);
    $element['#form_input'] = TRUE;
    return $element;
  }

  public static function valueCallback(&$element, $input, FormStateInterface $form_state) {
    if ($input === '') {
      // if (isset($element['#default_value'])) {
      //   return ceil($element['#default_value']);
      // }
    }
    return $input;
  }


  /**
   * {@inheritdoc}
   */
  public static function preRenderElement($element) {
    $element['#attached']  = [
      'library' => ['votingapi_widgets/voting'],
    ];
    $element['#default_value'] = ceil($element['#default_value']);
    if ($element['#plugin']) {
      $plugin = \Drupal::service('plugin.manager.voting_api_widget.processor')->createInstance($element['#plugin']);
      $options = [];
      if (isset($element['#allow_empty']) && (!isset($element['#required']) || $element['#required'] == FALSE)) {
        if (!isset($element['#empty_value'])) {
          $element['#empty_value'] = '--';
        }
        $options[$element['#empty_value']] = t('None');
        $element['#attributes']['data-rate-empty-value'] = $element['#empty_value'];
      }
      $options += $element['#options'];
      if ($plugin) {
        $options += $plugin->getValues();
      }
      $element['#options'] = $options;
      if (!isset($element['#attributes']['class'])) {
        $element['#attributes']['class'] = [];
      }
      $element['#attributes']['class'] += ['votingapi_widgets', $element['#plugin']];

      foreach ($element['#settings'] as $name => $setting) {
        $name = str_replace('_', '-', $name);
        $name = str_replace(' ', '-', $name);
        $element['#attributes']['data-rate-settings-' . $name] = Json::encode($setting);
      }
      if ($element['#default_value']) {
        $element['#attributes']['data-rate-default-value'] = $element['#default_value'];
      }
      $element = $plugin->attachLibrary($element);
    }

    $element['#field_suffix'] = '';
    if (isset($element['#show_results'])) {
      $element['#field_suffix'] = '<span class="result"></span>';
      if ($plugin && $vote = $element['#vote']) {
        $result = $plugin->getVoteSummary($vote);
        $result = render($result);
        $element['#field_suffix'] = '<span class="result">' . $result . '</span>';
      }
    }

    if (isset($element['#allow_empty'])) {
      $element['#field_suffix'] .= '<span class="clear">X</span>';
    }

    $element = parent::preRenderSelect($element);
    if ($element['#form_input']) {
      $element['#value'] = $element['#default_value'];
    }

    Element::setAttributes($element, array('id', 'name', 'size'));
    static::setAttributes($element, array('form-rate'));
    return $element;
  }

}
