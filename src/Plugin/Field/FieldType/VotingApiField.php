<?php

namespace Drupal\votingapi_widgets\Plugin\Field\FieldType;

use Drupal\Component\Utility\Random;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\TypedData\DataDefinition;
use Drupal\votingapi\Entity\VoteType;

/**
 * Plugin implementation of the 'voting_api_field' field type.
 *
 * @FieldType(
 *   id = "voting_api_field",
 *   label = @Translation("Voting api field"),
 *   description = @Translation("My Field Type"),
 *   default_widget = "voting_api_widget",
 *   default_formatter = "voting_api_formatter"
 * )
 */
class VotingApiField extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function defaultStorageSettings() {
    return [
      'vote_plugin' => '',
      'vote_type' => '',
      'status' => '',
    ] + parent::defaultStorageSettings();
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultFieldSettings() {
    return array(
      'result_function' => 'vote_average',
      'widget_format' => 'fivestar',
    ) + parent::defaultFieldSettings();
  }

  /**
   * {@inheritdoc}
   */
  public static function mainPropertyName() {
    return 'status';
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    // Prevent early t() calls by using the TranslatableMarkup.
    $properties['status'] = DataDefinition::create('integer')
      ->setLabel(t('Vote status'))
      ->setRequired(TRUE);

    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    return array(
      'columns' => array(
        'status' => array(
          'description' => 'Whether votes are allowed on this entity: 0 = no, 1 = closed (read only), 2 = open (read/write).',
          'type' => 'int',
          'default' => 0,
        ),
      ),
      'indexes' => array(),
      'foreign keys' => array(),
    );
  }

  /**
   * {@inheritdoc}
   */
  public static function generateSampleValue(FieldDefinitionInterface $field_definition) {
    $random = new Random();
    $values['status'] = $random->word(mt_rand(0, 1));
    return $values;
  }

  /**
   * {@inheritdoc}
   */
  public function storageSettingsForm(array &$form, FormStateInterface $form_state, $has_data) {
    $element = array();

    // @todo Inject entity storage once typed-data supports container injection.
    //   See https://www.drupal.org/node/2053415 for more details.
    $vote_plugins = \Drupal::service('plugin.manager.voting_api_widget.processor')->getDefinitions();
    $vote_options = [];

    foreach ($vote_plugins as $vote_plugin) {
      $vote_options[$vote_plugin['id']] = $vote_plugin['label'];
    }

    $vote_types = VoteType::loadMultiple();
    $options = array();
    foreach ($vote_types as $vote_type) {
      $options[$vote_type->id()] = $vote_type->label();
    }
    $element['vote_type'] = array(
      '#type' => 'select',
      '#title' => t('Vote type'),
      '#options' => $options,
      '#required' => TRUE,
      '#default_value' => $this->getSetting('vote_type'),
      '#disabled' => $has_data,
    );

    $element['vote_plugin'] = array(
      '#type' => 'select',
      '#title' => t('Vote type'),
      '#options' => $vote_options,
      '#required' => TRUE,
      '#default_value' => $this->getSetting('vote_type'),
      '#disabled' => $has_data,
    );

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    return FALSE;
  }

}
