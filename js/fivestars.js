/**
 * @file
 * Attaches fivestar rating.
 */

(function ($, Drupal) {
  Drupal.behaviors.fiveStarRating = {
    attach: function (context, settings) {
     $(context).find('.form-type-rate .fivestar').each(function () {
      var $select = $(this);
      var options = {
        theme: 'fontawesome-stars-o',
        showValues: false,
        showSelectedRating: false,
        readonly: ($select.attr('disabled')) ? true : false,
      };
      if ($select.data('rate-empty-value') !== undefined) {
        options.deselectable = false;
        options.allowEmpty = true;
        options.emptyValue = $select.data('rate-empty-value');
        $select.bind('clear',function(){
          $(this).barrating('clear');
        })
      }
      $select.once('processed').each(function(){
        $(this).barrating('show', options);;
      })
    });
    }
  };
})(jQuery, Drupal);
