/**
 * @file
 * Attaches is useful rating.
 */

(function ($, Drupal) {
  Drupal.behaviors.usefulRating = {
    attach: function (context, settings) {
     $('body').find('.useful').each(function () {
       var $this = $(this);
       $(this).find('select').once('processed').each(function () {
         $this.find('[type=submit]').hide();
         var $select = $(this);
         $select.after('<div class="useful-rating"><a href="#"><i class="fa fa-thumbs-down"></i></a><a href="#"><i class="fa fa-thumbs-up"></a></i></div>').hide();
         $this.find('.useful-rating a').eq(0).each(function () {
           $(this).bind('click',function (e) {
             e.preventDefault();
             $select.get(0).selectedIndex = 0;
             $this.find('[type=submit]').trigger('click');
             $this.find('a').addClass('disabled');
           })
         })
         $this.find('.useful-rating a').eq(1).each(function () {
           $(this).bind('click',function (e) {
             e.preventDefault();
             $select.get(0).selectedIndex = 1;
             $this.find('[type=submit]').trigger('click');
             $this.find('a').addClass('disabled');
           })
         })
       })
     });
    }
  };
})(jQuery, Drupal);
