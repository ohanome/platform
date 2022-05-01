(function ($, Drupal, drupalSettings) {
  function resetColorMode() {
    document.getElementsByTagName('html')[0].classList.remove('color-mode-light');
    document.getElementsByTagName('html')[0].classList.remove('color-mode-dark');
  }

  function setColorMode(color_mode) {
    resetColorMode();
    document.getElementsByTagName('html')[0].classList.add('color-mode-' + color_mode);
    window.localStorage.setItem('ohano.color_mode', color_mode);
    $.post(`${window.location.origin}/api/account/set/color-mode/${color_mode}`).then(res => {
      console.debug('changed color mode to ' + color_mode + ': ' + res);
    });
  }

  function setDarkMode() {
    setColorMode('dark');
  }

  function setLightMode() {
    setColorMode('light');
  }

  Drupal.behaviors.switchDarkLightMode = {
    attach: function (context, settings) {
      $('#setDarkMode').click(function () {
        setDarkMode();
      });

      $('#setLightMode').click(function () {
        setLightMode();
      });

      $(document).ready(function () {
        let darkModeOn = window.localStorage.getItem('ohano.color_mode');
        if ($('body').hasClass('authenticated')) {
          return;
        }
        if (darkModeOn === '1') {
          setDarkMode();
        } else {
          setLightMode();
        }
      });
    }
  }
})(jQuery, Drupal, drupalSettings);
