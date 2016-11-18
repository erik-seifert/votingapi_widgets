<?php

namespace Drupal\votingapi_widgets\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'voting_api_formatter' formatter.
 *
 * @FieldFormatter(
 *   id = "voting_api_formatter",
 *   label = @Translation("Voting api formatter"),
 *   field_types = {
 *     "voting_api_field"
 *   }
 * )
 */
class VotingApiFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'readonly' => FALSE,
      'style' => 'default',
      'show_results' => FALSE,
      'values' => [],
      // Implement default settings.
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $service = \Drupal::service('plugin.manager.votingapi.resultfunction');
    $plugins = $service->getDefinitions();
    $voteService = \Drupal::service('plugin.manager.voting_api_widget.processor');

    $options = [];
    $styles = [];

    $votePlugin = $voteService->createInstance($this->getFieldSetting('vote_plugin'));
    $styles = $votePlugin->getStyles();

    foreach ($plugins as $plugin_id => $plugin) {
      $plugin = $service->createInstance($plugin_id, $plugin);
      if ($plugin->getDerivativeId()) {
        $options[$plugin_id] = $plugin_id;
      }
    }

    return [
      // Implement settings form.
      'readonly' => [
        '#title' => t('Readonly'),
        '#type' => 'checkbox',
        '#default_value' => $this->getSetting('readonly'),
      ],
      'style' => [
        '#title' => t('Styles'),
        '#type' => 'select',
        '#options' => $styles,
        '#default_value' => $this->getSetting('style'),
      ],
      'show_results' => [
        '#title' => t('Show results'),
        '#type' => 'checkbox',
        '#default_value' => $this->getSetting('style'),
      ],
    ] + parent::settingsForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    // @TODO: Create a summary.
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    $entity = $items->getEntity();
    $field_settings = $this->getFieldSettings();
    $field_name = $this->fieldDefinition->getName();

    $vote_type = $field_settings['vote_type'];
    $vote_plugin = $field_settings['vote_plugin'];
    $readonly = $this->getSetting('readonly');

    if (!$items->status) {
      $readonly = TRUE;
    }

    $elements[] = [
      'vote_form' => [
        '#lazy_builder' => [
          'voting_api.lazy_loader:buildForm', [
            $vote_plugin,
            $entity->getEntityTypeId(),
            $entity->id(),
            $vote_type,
            $field_name,
            $this->getSetting('style'),
            $this->getSetting('show_results'),
            $readonly,
          ],
        ],
        '#create_placeholder' => TRUE,
      ],
      'results' => [],
    ];

    return $elements;
  }

}
