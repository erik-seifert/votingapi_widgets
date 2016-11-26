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
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $element['status'] = array(
      '#type' => 'radios',
      '#title' => t('Votes'),
      '#default_value' => isset($items->getValue('status')[0]['status']) ? $items->getValue('status')[0]['status'] : 1,
      '#options' => array(
        1 => t('Open'),
        0 => t('Closed'),
      ),
    );
    $entity_type = $this->fieldDefinition->getTargetEntityTypeId();
    $bundle = $this->fieldDefinition->getTargetBundle();
    $field_name = $this->fieldDefinition->getName();
    $permission = 'edit voting status on ' . $entity_type . ':' . $bundle . ':' . $field_name;
    $account = \Drupal::currentUser();
    $element['status']['#access'] = $account->hasPermission($permission);
    return $element;
  }

}
