<?php

namespace Drupal\votingapi_widgets\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\Html;

/**
 * Form controller for Campaign edit forms.
 *
 * @ingroup adspree_link_manager
 */
class BaseRatingForm extends ContentEntityForm {

  public $plugin;

  protected function actions(array $form, FormStateInterface $form_state) {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);
    $entity = $this->getEntity();
    $result_function = $this->getResultFunction($form_state);
    $form_id = Html::getUniqueId('vote-form');
    $rate_id = Html::getUniqueId($form_id);
    $plugin = $form_state->get('plugin');

    $form['#cache']['contexts'][] = 'user.permissions';
    $form['#cache']['contexts'][] = 'user.roles:authenticated';

    $form['#attributes']['id'] = $form_id;
    $form['value'] = [
      '#prefix' => '<div id="' . $rate_id . '">',
      '#suffix' => '</div>',
      '#type' => 'rate',
      '#show_results' => TRUE,
      '#required' => FALSE,
      '#attributes' => [
        'autocomplete' => 'off',
      ],
      '#ajax' => [
        'effect' => 'fade',
        'callback' => [$this, 'save'],
      ],
      '#allow_empty' => TRUE,
      '#empty_value' => '--------',
      '#vote' => $entity,
      '#readonly' => (!$form_state->get('read_only') && $plugin->canVote($entity)) ? FALSE : TRUE,
      '#plugin' => $plugin->getPluginId(),
      '#default_value' => $this->getResults($result_function),
    ];

    $form_state->setCached(FALSE);
    return $form;
  }

  /**
   * Get result function.
   */
  protected function getResultFunction(FormStateInterface $form_state) {
    $entity = $this->getEntity();
    return ($form_state->get('resultfunction')) ? $form_state->get('resultfunction') : 'vote_field_average:' . $entity->getVotedEntityType() . '.' . $entity->field_name->value;
  }

  /**
   * Get results.
   */
  public function getResults($result_function = FALSE, $reset = FALSE) {
    $entity = $this->entity;
    if ($reset) {
      drupal_static_reset(__FUNCTION__);
    }
    $resultCache = &drupal_static(__FUNCTION__);

    if (!$result_function && isset($resultCache[$entity->getEntityTypeId()][$entity->getVotedEntityId()])) {
      return $resultCache[$entity->getEntityTypeId()][$entity->getVotedEntityId()];
    }

    if (!$result_function) {
      $results = \Drupal::service('plugin.manager.votingapi.resultfunction')->getResults($entity->getVotedEntityType(), $entity->getVotedEntityId());
      if (!array_key_exists($entity->getEntityTypeId(), $results)) {
        return [];
      }
      $resultCache[$entity->getEntityTypeId()][$entity->getVotedEntityId()] = $results[$entity->getEntityTypeId()];
      return $resultCache[$entity->getEntityTypeId()][$entity->getVotedEntityId()];
    }

    if (isset($resultCache[$entity->getEntityTypeId()][$entity->getVotedEntityId()])) {
      return $resultCache[$entity->getEntityTypeId()][$entity->getVotedEntityId()][$result_function];
    }

    $results = \Drupal::service('plugin.manager.votingapi.resultfunction')->getResults($entity->getVotedEntityType(), $entity->getVotedEntityId());
    if (array_key_exists($entity->getEntityTypeId(), $results)) {
      $resultCache[$entity->getEntityTypeId()] = [
        $entity->getVotedEntityId() => $results[$entity->getEntityTypeId()],
      ];
      return $resultCache[$entity->getEntityTypeId()][$entity->getVotedEntityId()][$result_function];
    }
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $this->entity->setValue($form_state->getValue('value'));
    parent::save($form, $form_state);
    $form_state->setRebuild(TRUE);
    return $form;
  }

}
