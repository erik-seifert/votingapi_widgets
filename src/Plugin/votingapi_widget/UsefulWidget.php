<?php

namespace Drupal\votingapi_widgets\Plugin\votingapi_widget;

use Drupal\votingapi_widgets\Plugin\VotingApiWidgetBase;
use Drupal\Core\StringTranslation\StringTranslationTrait;

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

  use StringTranslationTrait;

  /**
   * Vote form.
   */
  public function buildForm($entity_type, $entity_bundle, $entity_id, $vote_type, $field_name, $style, $show_results, $read_only = FALSE, $show_own_vote = FALSE) {
    $form = $this->getForm($entity_type, $entity_bundle, $entity_id, $vote_type, $field_name, $style, $show_results, $read_only, $show_own_vote);
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
      'default' => $this->t('Default'),
    ];
  }

}
