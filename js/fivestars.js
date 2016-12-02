/**
 * @file
 * Attaches fivestar rating.
 */

(function ($, Drupal) {
  Drupal.behaviors.fiveStarRating = {
    attach: function (context, settings) {
     $('body').find('.fivestar').each(function () {
      var $this = $(this);
      var $select = $this.find('select');
      var value = $select.data('result-value');
      var isEdit = $select.data('is-edit');
      if (isEdit) {
        value = $select.val();
      }
      if (!value) {
        value = -1;
      }
      var options = {
        theme: ($select.data('style') == 'default') ? 'css-stars' : $select.data('style'),
        initialRating: value,
        allowEmpty: true,
        emptyValue: '',
        readonly: ($select.attr('disabled')) ? true : false,
        onSelect: function (value, text) {
          if (isEdit) {
            return;
          }
          $this.find('select').barrating('readonly', true);
          $this.find('input[type=submit]').trigger('click');
          $this.find('a').addClass('disabled');
        },
      };
      $this.find('select').once('processed').barrating('show', options);
      $this.find('input[type=submit]').hide();
    });
    }
  };
})(jQuery, Drupal);
