/**
 * @file
 * Attaches voting commong js.
 */

(function ($, Drupal) {
  Drupal.behaviors.votingApiWidgets = {
    attach: function (context, settings) {
     $(context).find('.form-type-rate').once('processed').each(function () {
       var $container = $(this);
       var $rate = $(this).find('select').eq(0);
       var _select = $(this).find('select').get(0);

       /**
        * Bind click clear handler
        */
       if ($rate.data('rate-empty-value') !== undefined) {
         $(this).find('span.clear').bind('click', function () {
           console.log('CLEAR');
            $.each(_select.options, function (i,o){
              if (o.value == $rate.data('rate-empty-value')) {
                _select.selectedIndex = i;
                $rate.trigger('clear');
              }
            });
         })
       }
       /**
        * Bind click clear handler
        */
       $rate.bind('change', function () {
          var i = this.selectedIndex;
          var value = $(this.options[this.selectedIndex]).text();
          $container.find('span.result').text(value);
          $(this).parents('form').eq(0).find('[type=submit]').trigger('click');
       })
     });
    }
  };
})(jQuery, Drupal);
