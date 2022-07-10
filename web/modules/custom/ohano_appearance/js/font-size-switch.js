(function ($, Drupal, drupalSettings) {
  function resetFontSize() {
    document.getElementsByTagName('html')[0].classList.remove('font-size-xxs');
    document.getElementsByTagName('html')[0].classList.remove('font-size-xs');
    document.getElementsByTagName('html')[0].classList.remove('font-size-s');
    document.getElementsByTagName('html')[0].classList.remove('font-size-m');
    document.getElementsByTagName('html')[0].classList.remove('font-size-l');
    document.getElementsByTagName('html')[0].classList.remove('font-size-xl');
    document.getElementsByTagName('html')[0].classList.remove('font-size-xxl');
  }

  function setFontSize(font_size) {
    resetFontSize();
    document.getElementsByTagName('html')[0].classList.add('font-size-' + font_size);
    window.localStorage.setItem('ohano.font-size', font_size);
    /** @phpcs:disable */
    $.post(`${window.location.origin}/api/account/set/font-size/${font_size}`).then(res => {
      /** @phpcs:enable */
      console.debug('changed font size to ' + font_size + ': ' + res);
    });
  }

  function setFontSizeXXS() {
    setFontSize('xxs');
  }

  function setFontSizeXS() {
    setFontSize('xs');
  }

  function setFontSizeS() {
    setFontSize('s');
  }

  function setFontSizeM() {
    setFontSize('m');
  }

  function setFontSizeL() {
    setFontSize('l');
  }

  function setFontSizeXL() {
    setFontSize('xl');
  }

  function setFontSizeXXL() {
    setFontSize('xxl');
  }

  Drupal.behaviors.switchFontSize = {
    attach: function (context, settings) {
      $('#setFontSizeXXS').click(function () {
        setFontSizeXXS();
      });

      $('#setFontSizeXS').click(function () {
        setFontSizeXS();
      });

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
        if ($('body').hasClass('authenticated')) {
          return;
        }

        switch (fontSize) {
          case 'xxs':
            setFontSizeXXS();
            break;

          case 'xs':
            setFontSizeXS();
            break;

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
