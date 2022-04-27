(function ($, Drupal, drupalSettings) {
  function resetFontSize() {
    document.getElementsByTagName('html')[0].classList.remove('font-s');
    document.getElementsByTagName('html')[0].classList.remove('font-m');
    document.getElementsByTagName('html')[0].classList.remove('font-l');
    document.getElementsByTagName('html')[0].classList.remove('font-xl');
    document.getElementsByTagName('html')[0].classList.remove('font-xxl');
  }

  function setFontSizeS() {
    resetFontSize();
    document.getElementsByTagName('html')[0].classList.add('font-s');
    window.localStorage.setItem('ohano.font-size', 's');
  }

  function setFontSizeM() {
    resetFontSize();
    document.getElementsByTagName('html')[0].classList.add('font-m');
    window.localStorage.setItem('ohano.font-size', 'm');
  }

  function setFontSizeL() {
    resetFontSize();
    document.getElementsByTagName('html')[0].classList.add('font-l');
    window.localStorage.setItem('ohano.font-size', 'l');
  }

  function setFontSizeXL() {
    resetFontSize();
    document.getElementsByTagName('html')[0].classList.add('font-xl');
    window.localStorage.setItem('ohano.font-size', 'xl');
  }

  function setFontSizeXXL() {
    resetFontSize();
    document.getElementsByTagName('html')[0].classList.add('font-xxl');
    window.localStorage.setItem('ohano.font-size', 'xxl');
  }

  Drupal.behaviors.switchFontSize = {
    attach: function (context, settings) {
      $('#setFontSizeS').click(function () {
        setFontSizeS();
      });

      $('#setFontSizeM').click(function () {
        setFontSizeM();
      });

      $('#setFontSizeL').click(function () {
        setFontSizeL();
      });

      $('#setFontSizeXL').click(function () {
        setFontSizeXL();
      });

      $('#setFontSizeXXL').click(function () {
        setFontSizeXXL();
      });

      $(document).ready(function () {
        let fontSize = window.localStorage.getItem('ohano.font-size');
        switch (fontSize) {
          case 's':
            setFontSizeS();
            break;

          case 'm':
            setFontSizeM();
            break;

          case 'l':
            setFontSizeL();
            break;

          case 'xl':
            setFontSizeXL();
            break;

          case 'xxl':
            setFontSizeXXL();
            break;

          default:
            resetFontSize();
        }
      });
    }
  }
})(jQuery, Drupal, drupalSettings);
