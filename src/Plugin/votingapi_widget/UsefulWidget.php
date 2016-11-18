<?php

namespace Drupal\votingapi_widgets\Plugin\votingapi_widget;

use Drupal\votingapi_widgets\Plugin\VotingApiWidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\ContentEntityInterface;

/**
 * Assigns ownership of a node to a user.
 *
 * @VotingApiWidget(
 *   id = "useful",
 *   label = @Translation("Usefull rating"),
 *   values = {
 *    -1 = @Translation("Poor"),
 *    1 = @Translation("Not so poor"),
 *   },
 * )
 */
class UsefulWidget extends VotingApiWidgetBase {

  /**
   * Vote form.
   */
  public function buildForm($entity_type, $entity_id, $vote_type, $field_name, $style, $show_results, $read_only = FALSE) {
    $storage = \Drupal::service('entity.manager')->getStorage('vote');
    $currentUser = \Drupal::currentUser();
    $voteData = [
      'entity_type' => $entity_type,
      'entity_id'   => $entity_id,
      'type'      => $vote_type,
      'field_name'  => $field_name,
    ];
    $vote = $storage->create($voteData);

    if ($currentUser->isAnonymous()) {
      $voteData['vote_source'] = \Drupal::service('request_stack')->getCurrentRequest()->getClientIp();
    }

    $query = \Drupal::entityQuery('vote');
    foreach ($voteData as $key => $value) {
      $query->condition($key, $value);
    }

    $votes = $query->execute();
    if ($votes && count($votes) > 0) {
      $vote = $storage->load(array_shift($votes));
    }

    $form = \Drupal::service('entity.form_builder')->getForm($vote, 'votingapi_useful', [
      'read_only' => $read_only,
      'options' => $this->getPluginDefinition()['values'],
      'style' => $style,
      'show_results' => $show_results,
      'plugin' => $this,
    ]);

    $build = [
      'rating' => [
        '#theme' => 'container',
        '#attributes' => [
          'class' => [
            'votingapi-widgets',
            'useful',
            ($read_only) ? 'read_only' : '',
          ],
        ],
        '#children' => [
          'form' => $form,
        ],
      ],
      '#attached' => [
        'library' => ['votingapi_widgets/useful'],
      ],
    ];
    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function getStyles() {
    return [
      'default' => t('Default'),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getVoteSummary($form, FormStateInterface $form_state, ContentEntityInterface $vote) {
    return [];
  }

}
