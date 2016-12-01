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
      var value = $select.data('default-value');
      var isPreview = $select.data('is-edit');
      var style = settings.votingapi_widgets.fivestar.style;
      if (!value) {
        value = -1;
      }
      var options = {
        theme: style == 'default' ? 'css-stars' : style,
        showSelectedRating: true,
        initialRating: value,
        allowEmpty: true,
        emptyValue: '',
        readonly: settings.votingapi_widgets.fivestar.read_only ? true : false,
        onSelect: function (value, text) {
          if (isPreview) {
            return;
          }
          $this.find('select').barrating('readonly', true);
          $this.find('input[type=submit]').trigger('click');
          $this.find('a').addClass('disabled');
          $this.trigger('fivestar.change');
        }
      };
      $this.find('select').once('processed').barrating('show', options);
      $this.find('input[type=submit]').hide();
    });
    }
  };
})(jQuery, Drupal);
