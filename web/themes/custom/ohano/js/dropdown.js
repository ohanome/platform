(function ($, Drupal, drupalSettings) {
  Drupal.behaviors.handleDropdown = {
    attach: function (context, settings) {
      let arrow = '&nbsp;<i class="fa fa-angle-down dropdown-arrow"></i>';
      let dropdownBase = '.dropdown-base';
      let $dropdownBase = $(dropdownBase);

      $(document).ready(function () {
        $dropdownBase.html($dropdownBase.html() + arrow);
      })

      $dropdownBase.click(function () {
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
