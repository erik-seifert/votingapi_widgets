<?php

namespace Drupal\votingapi_widgets\Plugin\votingapi_widget;

use Drupal\votingapi_widgets\Plugin\VotingApiWidgetBase;
use Drupal\Core\StringTranslation\StringTranslationTrait;

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

  use StringTranslationTrait;

  /**
   * Vote form.
   */
  public function buildForm($entity_type, $entity_bundle, $entity_id, $vote_type, $field_name, $settings) {
    $form = $this->getForm($entity_type, $entity_bundle, $entity_id, $vote_type, $field_name, $settings);
    $build = [
      'rating' => [
        '#theme' => 'container',
        '#attributes' => [
          'class' => [
            'votingapi-widgets',
            'fivestar',
            ($settings['readonly'] === 1) ? 'read_only' : '',
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
  public function getInitialVotingElement(array &$form) {
    $form['value']['#prefix'] = '<div class="votingapi-widgets fivestar">';
    $form['value']['#attached'] = [
      'library' => ['votingapi_widgets/fivestar'],
    ];
    $form['value']['#suffix'] = '</div>';
    $form['value']['#attributes'] = [
      'data-style' => 'default',
      'data-is-edit' => 1,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getStyles() {
    return [
      'default' => $this->t('Default'),
      'bars-horizontal' => $this->t('Bars horizontal'),
      'css-stars' => $this->t('Css stars'),
      'bars-movie' => $this->t('Bars movie'),
      'bars-pill' => $this->t('Bars pill'),
      'bars-square' => $this->t('Bars square'),
      'fontawesome-stars-o' => $this->t('Fontawesome stars-o'),
      'fontawesome-stars' => $this->t('Fontawesome stars'),
      'bootstrap-stars' => $this->t('Bootstrap stars'),
    ];
  }

}
