/**
 * @file
 * Attaches is useful rating.
 */

(function ($, Drupal) {
  Drupal.behaviors.usefulRating = {
    attach: function (context, settings) {
     $('body').find('.useful').each(function () {

     });
    }
  };
})(jQuery, Drupal);
