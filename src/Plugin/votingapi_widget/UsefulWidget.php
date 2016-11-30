<?php

namespace Drupal\votingapi_widgets\Plugin\votingapi_widget;

use Drupal\votingapi_widgets\Plugin\VotingApiWidgetBase;

/**
 * Assigns ownership of a node to a user.
 *
 * @VotingApiWidget(
 *   id = "useful",
 *   label = @Translation("Usefull rating"),
 *   values = {
 *    -1 = @Translation("Not useful"),
 *    1 = @Translation("Useful"),
 *   },
 * )
 */
class UsefulWidget extends VotingApiWidgetBase {

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
  public function getInitialVotingElement(array &$form) {
    $form['value']['#prefix'] = '<div class="votingapi-widgets useful">';
    $form['value']['#attached']  = [
      'library' => ['votingapi_widgets/useful'],
    ];
    $form['value']['#suffix'] = '</div>';
  }

  /**
   * {@inheritdoc}
   */
  public function getStyles() {
    return [
      'default' => t('Default'),
    ];
  }

}
