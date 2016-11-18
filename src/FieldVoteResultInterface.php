<?php

namespace Drupal\votingapi_widgets;

/**
 * Interface for field vote results plugins.
 */
interface FieldVoteResultInterface {

  /**
   * Get all votes for a field.
   */
  public function getVotesForField($votes);

}
