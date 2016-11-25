<?php

namespace Drupal\votingapi_widgets\Plugin\votingapi_widget;

use Drupal\votingapi_widgets\Plugin\VotingApiWidgetBase;

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
  public function buildForm($entity_type, $entity_bundle, $entity_id, $vote_type, $field_name, $style, $show_results, $read_only = FALSE) {
    $form = $this->getForm($entity_type, $entity_bundle, $entity_id, $vote_type, $field_name, $style, $show_results, $read_only);
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
