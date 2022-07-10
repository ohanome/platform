(function ($, Drupal, drupalSettings) {
  Drupal.behaviors.processProfileAddForm = {
    attach: function (context, settings) {
      let $nameField = $('input#edit-profile-name');

      let process = ($elem) => {
        let val = $elem.val();

        val = val.replace(/[^a-zA-Z0-9\-_.]/, '-');

        if (!new RegExp(/[a-zA-Z0-9]$/).test(val) || !new RegExp(/^[a-zA-Z]/).test(val)) {
          $elem.addClass('error');
          $elem.parents('form').find('input[type="submit"]').attr('disabled', 'disabled');
        } else {
          $elem.removeClass('error');
          // phpcs:ignore
          $elem.parents('form').find('input[type="submit"]').attr('disabled', null);
        }

        $elem.val(val);
      }

      $nameField.once('pressed').keypress((e) => {
        setTimeout(() => {
          process($(e.target));
        }, 100);
      });
    }
  };
})(jQuery, Drupal, drupalSettings);
