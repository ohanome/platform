(function ($, Drupal, drupalSettings) {
  Drupal.behaviors.handleDropdown = {
    attach: function (context, settings) {
      let arrow = '&nbsp;<i class="fa fa-angle-down dropdown-arrow"></i>';
      let dropdownBase = '.dropdown-base';
      let $dropdownBase = $(dropdownBase);

      $(document).once('ready').ready(function () {
        $dropdownBase.each((k, v) => {
          if ($(v).find('.dropdown-arrow').length === 0) {
            $(v).html($(v).html() + arrow);
          }
        });
      })

      $dropdownBase.once('clicked').click(function () {
        let parent = $(this).parent('.dropdown');
        if (parent.hasClass('active')) {
          parent.removeClass('active');

          $(this).find('.dropdown-arrow').css({"transform": "rotate(0deg)"});
        } else {
          parent.addClass('active');

          $(this).find('.dropdown-arrow').css({"transform": "rotate(180deg)"});
        }
      })
    }
  }
})(jQuery, Drupal, drupalSettings);
