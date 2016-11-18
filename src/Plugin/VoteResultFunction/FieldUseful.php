<?php

namespace Drupal\votingapi_widgets\Plugin\VoteResultFunction;

use Drupal\votingapi_widgets\FieldVoteResultBase;

/**
 * A sum of a set of votes.
 *
 * @VoteResultFunction(
 *   id = "vote_field_useful",
 *   label = @Translation("Useful rating"),
 *   description = @Translation("The average vote value."),
 *   deriver = "Drupal\votingapi_widgets\Plugin\Derivative\FieldResultFunction",
 * )
 */
class FieldUseful extends FieldVoteResultBase {

  /**
   * {@inheritdoc}
   */
  public function calculateResult($votes) {
    $total = 0;
    $votes = $this->getVotesForField($votes);
    foreach ($votes as $vote) {
      if ($vote->value->value == 1) {
        $total++;
      }
    }
    return $total;
  }

}
