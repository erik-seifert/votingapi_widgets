<?php

namespace Drupal\votingapi_widgets\Plugin\votingapi_widget;

use Drupal\votingapi_widgets\Plugin\VotingApiWidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\ContentEntityInterface;

/**
 * Assigns ownership of a node to a user.
 *
 * @VotingApiWidget(
 *   id = "fivestar",
 *   label = @Translation("Fivestar rating"),
 *   values = {
 *    1 = @Translation("Poor"),
 *    2 = @Translation("Not so poor"),
 *    3 = @Translation("average"),
 *    4 = @Translation("good"),
 *    5 = @Translation("very good"),
 *   },
 * )
 */
class FiveStarWidget extends VotingApiWidgetBase {

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

    $form = \Drupal::service('entity.form_builder')->getForm($vote, 'votingapi_fivestar', [
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
            'fivestar',
            ($read_only) ? 'read_only' : '',
          ],
        ],
        '#children' => [
          'form' => $form,
        ],
      ],
      '#attached' => [
        'library' => ['votingapi_widgets/fivestar'],
      ],
    ];
    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function getVoteSummary($form, FormStateInterface $form_state, ContentEntityInterface $vote) {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function getStyles() {
    return [
      'default' => t('Default'),
      'bars-horizontal' => t('Bars horizontal'),
      'css-stars' => t('Css stars'),
      'bars-movie' => t('Bars movie'),
      'bars-pill' => t('Bars pill'),
      'bars-square' => t('Bars square'),
      'fontawesome-stars-o' => t('Fontawesome stars-o'),
      'fontawesome-stars' => t('Fontawesome stars'),
      'bootstrap-stars' => t('Bootstrap stars'),
    ];
  }

}
