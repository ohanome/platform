(function ($, Drupal, drupalSettings) {
  function setDarkMode() {
    document.getElementsByTagName('html')[0].classList.add('dark');
    window.localStorage.setItem('ohano.darkmode', '1');
  }

  function setLightMode() {
    document.getElementsByTagName('html')[0].classList.remove('dark');
    window.localStorage.setItem('ohano.darkmode', '0');
  }

  Drupal.behaviors.switchDarkLightMode = {
    attach: function (context, settings) {
      $('#setDarkMode').click(function () {
        setDarkMode();
      });

      $('#setLightMode').click(function () {
        setLightMode();
      });

      $('#setBrightnessToSystem').click(function () {
        if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
          setDarkMode();
        } else {
          setLightMode();
        }
      });

      $(document).ready(function () {
        let darkModeOn = window.localStorage.getItem('ohano.darkmode');
        if (darkModeOn === '1') {
          setDarkMode();
        } else {
          setLightMode();
        }
      });
    }
  }
})(jQuery, Drupal, drupalSettings);
