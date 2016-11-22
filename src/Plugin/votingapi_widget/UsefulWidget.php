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
  public function getStyles() {
    return [
      'default' => t('Default'),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getVoteSummary($form, FormStateInterface $form_state, ContentEntityInterface $entity) {
    $results = $this->getResults($entity);
    $average = $results['vote_field_useful:' . $entity->getVotedEntityType() . '.' . $entity->field_name->value];

    return [
      '#markup' => t('@average Users found this content useful', ['@average' => $average]),
    ];
  }

}
