(function ($, Drupal, drupalSettings) {
  Drupal.behaviors.profileSwitchTabs = {
    attach: function (context, settings) {
      $('.profile-card .info-tabs li').once('clicked').click((event) => {
        let tab = $(event.target);
        if ($(event.target).is("i")) {
          tab = tab.parent();
        }

        let id = tab.data('for');

        $('.profile-card .info-tabs li.active').removeClass('active');
        tab.addClass('active');

        $('.profile-card .info > .section.active').removeClass('active');
        $('.profile-card .info > .section' + id).addClass('active');
      })
    }
  }
})(jQuery, Drupal, drupalSettings);
