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
      'readonly'      => FALSE,
      'style'         => 'default',
      'show_results'  => FALSE,
      'values'        => [],
      'show_own_vote' => FALSE,
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
      'style'        => [
        '#title'         => $this->t('Styles'),
        '#type'          => 'select',
        '#options'       => $styles,
        '#default_value' => $this->getSetting('style'),
      ],
      'readonly'     => [
        '#title'         => $this->t('Readonly'),
        '#type'          => 'checkbox',
        '#default_value' => $this->getSetting('readonly'),
      ],
      'show_results' => [
        '#title'         => $this->t('Show results'),
        '#type'          => 'checkbox',
        '#default_value' => $this->getSetting('show_results'),
      ],
      'show_own_vote' => [
        '#title'         => $this->t('Show own vote'),
        '#description'   => $this->t('Show own cast vote instead of results. (Useful on add/ edit forms with rate widget).'),
        '#type'          => 'checkbox',
        '#default_value' => $this->getSetting('show_own_vote'),
      ],
    ] + parent::settingsForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    $summary[] = $this->t('Styles: @styles', ['@styles' => $this->getSetting('style')]);
    $summary[] = $this->t('Readonly: @readonly', ['@readonly' => $this->getSetting('readonly') ? $this->t('yes') : $this->t('no')]);
    $summary[] = $this->t('Show results: @results', ['@results' => $this->getSetting('show_results') ? $this->t('yes') : $this->t('no')]);
    $summary[] = $this->t('Show own vote: @show_own_vote', ['@show_own_vote' => $this->getSetting('show_own_vote') ? $this->t('yes') : $this->t('no')]);

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
    $show_own_vote = $this->getSetting('show_own_vote') ? TRUE : FALSE;

    if ($items->status === "0") {
      $readonly = TRUE;
    }

    $elements[] = [
      'vote_form' => [
        '#lazy_builder'       => [
          'voting_api.lazy_loader:buildForm',
          [
            $vote_plugin,
            $entity->getEntityTypeId(),
            $entity->bundle(),
            $entity->id(),
            $vote_type,
            $field_name,
            $this->getSetting('style'),
            $this->getSetting('show_results'),
            $readonly,
            $show_own_vote,
          ],
        ],
        '#create_placeholder' => TRUE,
      ],
      'results'   => [],
    ];

    return $elements;
  }

}
