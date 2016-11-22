<?php

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
    $total = 0;
    $votes = $this->getVotesForField($votes);
    foreach ($votes as $vote) {
      $total += (int) $vote->getValue();
    }
    if ($total == 0) {
      return 0;
    }
    return ($total / count($votes));
  }

}
