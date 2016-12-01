<?php

namespace Drupal\votingapi_widgets\Form;

use Drupal\Core\Form\FormBuilder;
use Drupal\Core\Form\FormStateInterface;

/**
 * TODO: remove this class once https://www.drupal.org/node/766146 is fixed
 *
 * Class VotingApiWidgetsFormBuilder
 * @package Drupal\votingapi_widgets\Form
 */
class VotingApiWidgetsFormBuilder extends FormBuilder {
  public function getFormId($form_arg, FormStateInterface &$form_state) {
    $form_id = parent::getFormId($form_arg, $form_state);

    $additional_form_id_parts = [];
    $additional_form_id_parts[] = $form_state->get('entity_type');
    $additional_form_id_parts[] = $form_state->get('entity_bundle');
    $additional_form_id_parts[] = $form_state->get('entity_id');
    $additional_form_id_parts[] = $form_state->get('vote_type');
    $additional_form_id_parts[] = $form_state->get('field_name');
    $form_id = implode('_', $additional_form_id_parts) . '__' . $form_id;
    return $form_id;
  }
}