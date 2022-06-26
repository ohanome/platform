(function ($, Drupal, drupalSettings) {
  function urlify(text) {
    let urlRegex = /([^"])(https?:\/\/\S+)([^"])/g;
    return text.replace(urlRegex, '<a href="$2">$2</a>')
    // or alternatively
    // return text.replace(urlRegex, '<a href="$1">$1</a>')
  }

  Drupal.behaviors.processTextFieldLinks = {
    attach: function (context, settings) {
      $(document).once('loaded').ready(function () {
        let content = $('.node-type--update div');
        //content.text(urlify(content.text()));
      });
    }
  }

})(jQuery, Drupal, drupalSettings);
