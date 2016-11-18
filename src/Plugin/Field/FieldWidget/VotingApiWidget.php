<?php

namespace Drupal\votingapi_widgets\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

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

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'size' => 60,
      'placeholder' => '',
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $elements = [];

    return $elements;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];

    $summary[] = t('Textfield size: @size', ['@size' => $this->getSetting('size')]);
    if (!empty($this->getSetting('placeholder'))) {
      $summary[] = t('Placeholder: @placeholder', ['@placeholder' => $this->getSetting('placeholder')]);
    }

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $element['status'] = array(
      '#type' => 'radios',
      '#title' => t('Votes'),
      '#title_display' => 'invisible',
      '#default_value' => 1,
      '#options' => array(
        1 => t('Open'),
        0 => t('Closed'),
      ),
    );
    return $element;
  }

}
