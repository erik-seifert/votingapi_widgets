<?php

namespace Drupal\votingapi_widgets;

use Drupal\votingapi\VoteResultFunctionBase;

/**
 * FieldVoteResultBase class.
 */
class FieldVoteResultBase extends VoteResultFunctionBase implements FieldVoteResultInterface {

  /**
   * Get votes for field.
   */
  public function getVotesForField($votes) {
    $plugin_id  = explode('.', $this->getDerivativeId());
    $field_name = $plugin_id[1];
    foreach ($votes as $key => $vote) {
      if ($vote->field_name->value != $field_name) {
        unset($votes[$key]);
      }
    }
    return $votes;
  }

  /**
   * Calculate results.
   */
  public function calculateResult($votes) {
    return count($votes);
  }

}
