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

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\adspree_link_manager\Entity\Campaign */
    $form = parent::buildForm($form, $form_state);
    $entity = $this->getEntity();
    $form_state->disableCache();
    $form_state->setRebuild(TRUE);

    $options = $form_state->get('options');

    $form_id = Html::getUniqueId('vote-form');

    $form['#prefix'] = '<div id="' . $form_id . '">';
    $form['#suffix'] = '</div>';

    $form['value'] = [
      '#type' => 'select',
      '#options' => $options,
      '#attributes' => [
        'autocomplete' => 'off',
        'data-default-value' => ($this->getResults($form_state->get('resultfunction'))) ? $this->getResults($form_state->get('resultfunction')) : -1,
        'data-style' => ($form_state->get('style')) ? $form_state->get('style') : 'default',
      ],
      '#default_value' => $this->getResults($form_state->get('resultfunction')),
    ];
    if ($form_state->get('read_only')) {
      $form['value']['#attributes']['disabled'] = 'disabled';
    }

    if ($form_state->get('show_results')) {
      $form['result'] = [
        '#theme' => 'container',
        '#attributes' => [
          'class' => ['vote-result'],
        ],
        '#children' => [],
      ];

      $results = $this->getResults();
      $form['result']['#children'] = [];
      foreach ($results as $id => $result) {
        if (strrpos($id, $entity->get('field_name')->value) !== FALSE) {
          $form['result']['#children'][$id] = [
            '#theme' => 'container',
            '#attributes' => ['class' => ['result-' . $id]],
            '#children' => [
              '#markup' => $result,
            ],
          ];
        }
      }
    }

    $form['actions']['submit'] += [
      '#type' => 'button',
      '#ajax' => [
        'callback' => array($this, 'ajaxSubmit'),
        'event' => 'click',
        'wrapper' => $form_id,
        'progress' => [
          'method' => 'replace',
        ],
      ],
    ];
    return $form;
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
      $resultCache[$entity->getEntityTypeId()][$entity->getVotedEntityId()] = $results[$entity->getEntityTypeId()];
      return $resultCache[$entity->getEntityTypeId()][$entity->getVotedEntityId()];
    }

    if (isset($resultCache[$entity->getEntityTypeId()][$entity->getVotedEntityId()])) {
      return $resultCache[$entity->getEntityTypeId()][$entity->getVotedEntityId()][$result_function];
    }

    $results = \Drupal::service('plugin.manager.votingapi.resultfunction')->getResults($entity->getVotedEntityType(), $entity->getVotedEntityId());
    if ($results[$entity->getEntityTypeId()]) {
      $resultCache[$entity->getEntityTypeId()] = [
        $entity->getVotedEntityId() => $results[$entity->getEntityTypeId()],
      ];
      return $resultCache[$entity->getEntityTypeId()][$entity->getVotedEntityId()][$result_function];
    }
  }

  /**
   * Ajax submit handler.
   */
  public function ajaxSubmit(array $form, FormStateInterface $form_state) {
    $this->save($form, $form_state);
    $form['value']['#default_value'] = $this->getResults($form_state->get('resultfunction'), TRUE);
    $form['value']['#attributes']['data-default-value'] = $this->getResults($form_state->get('resultfunction'));
    if ($form_state->get('show_results')) {
      $results = $this->getResults();
      $form['result']['#children'] = [];
      foreach ($results as $id => $result) {
        if (strrpos($id, $this->getEntity()->get('field_name')->value) !== FALSE) {
          $form['result']['#children'][$id] = [
            '#theme' => 'container',
            '#attributes' => ['class' => ['result-' . $id]],
            '#children' => [
              '#markup' => $result,
            ],
          ];
        }
      }
    }
    $form_state->setRebuild(TRUE);
    $form_state->disableCache();
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $status = parent::save($form, $form_state);
    return $status;
  }

}
