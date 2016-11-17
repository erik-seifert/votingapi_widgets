<?php

/**
 * @file
 * Contains \Drupal\votingapi\Plugin\VoteResultFunction\Average.
 */

namespace Drupal\votingapi_widgets\Plugin\VoteResultFunction;

use Drupal\votingapi_widgets\FieldVoteResultBase;

/**
 * A sum of a set of votes.
 *
 * @VoteResultFunction(
 *   id = "vote_field_average",
 *   label = @Translation("Average"),
 *   description = @Translation("The average vote value."),
 *   deriver = "Drupal\votingapi_widgets\Plugin\Derivative\FieldResultFunction",
 * )
 */
class FieldAverage extends FieldVoteResultBase {

  /**
   * {@inheritdoc}
   */
  public function calculateResult($votes) {
    $votes = $this->getVotesForField($votes);
    foreach ($votes as $vote) {
      $total += $vote->getValue();
    }
    return ($total / count($votes));
  }

}
