<?php

namespace Drupal\votingapi_widgets\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Plugin implementation of the 'voting_api_widget' widget.
 *
 * @FieldWidget(
 *   id = "voting_api_widget",
 *   label = @Translation("Voting api widget"),
 *   field_types = {
 *     "voting_api_field"
 *   }
 * )
 */
class VotingApiWidget extends WidgetBase {

  use StringTranslationTrait;

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return ['show_initial_vote' => 0];
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $form['show_initial_vote'] = [
      '#type' => 'select',
      '#options' => [0 => $this->t('Show not initial voting'), 1 => $this->t('Show initial voting')],
      '#default_value' => $this->getSetting('show_initial_vote'),
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $entity = $items->getEntity();
    $element['status'] = array(
      '#type' => 'radios',
      '#title' => $this->t('Votes'),
      '#default_value' => isset($items->getValue('status')[0]['status']) ? $items->getValue('status')[0]['status'] : 1,
      '#options' => array(
        1 => $this->t('Open'),
        0 => $this->t('Closed'),
      ),
    );
    $entity_type = $this->fieldDefinition->getTargetEntityTypeId();
    $bundle = $this->fieldDefinition->getTargetBundle();
    $field_name = $this->fieldDefinition->getName();
    $permission = 'edit voting status on ' . $entity_type . ':' . $bundle . ':' . $field_name;
    $account = \Drupal::currentUser();
    $element['status']['#access'] = $account->hasPermission($permission);

    $plugin = $this->fieldDefinition->getSetting('vote_plugin');
    /**
     * @var VotingApiWidgetBase $plugin
     */
    $plugin = \Drupal::service('plugin.manager.voting_api_widget.processor')->createInstance($plugin);

    $permission = 'vote on ' . $entity_type . ':' . $bundle . ':' . $field_name;
    $options = [
      '' => $this->t('None'),
    ];

    $vote_type = 'vote';
    $vote = $plugin->getEntityForVoting($entity_type, $bundle, $entity->id(), $vote_type, $field_name);
    $options += $plugin->getValues();
    $element['value'] = [
      '#type' => 'select',
      '#title' => $this->t('Your vote'),
      '#options' => $options,
      '#default_value' => $vote->getValue(),
      '#access' => ($this->getSetting('show_initial_vote') && $account->hasPermission($permission)) ? TRUE : FALSE,
    ];

    $plugin->getInitialVotingElement($element);

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    $summary[] = $this->t(
      'Show initial vote: @show_initial_vote',
      ['@show_initial_vote' => $this->getSetting('show_initial_vote') ? $this->t('yes') : $this->t('no')]
    );

    return $summary;
  }

}
