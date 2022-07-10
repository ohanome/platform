(function ($, Drupal, drupalSettings) {
  function resetColorShade() {
    document.getElementsByTagName('html')[0].classList.remove('color-shade-slate');
    document.getElementsByTagName('html')[0].classList.remove('color-shade-gray');
    document.getElementsByTagName('html')[0].classList.remove('color-shade-zinc');
    document.getElementsByTagName('html')[0].classList.remove('color-shade-neutral');
    document.getElementsByTagName('html')[0].classList.remove('color-shade-stone');
  }

  function setColorShade(shade) {
    resetColorShade();
    document.getElementsByTagName('html')[0].classList.add('color-shade-' + shade);
    window.localStorage.setItem('ohano.color_shade', shade);
    /** @phpcs:disable */
    $.post(`${window.location.origin}/api/account/set/color-shade/${shade}`).then(res => {
      /** @phpcs:enable */
      console.debug('changed color shade to ' + shade + ': ' + res);
    });
  }

  function setColorShadeSlate() {
    setColorShade('slate');
  }

  function setColorShadeGray() {
    setColorShade('gray');
  }

  function setColorShadeZinc() {
    setColorShade('zinc');
  }

  function setColorShadeNeutral() {
    setColorShade('neutral');
  }

  function setColorShadeStone() {
    setColorShade('stone');
  }

  Drupal.behaviors.switchColorShade = {
    attach: function (context, settings) {
      $('#setColorShadeSlate').once('clicked').click(function () {
        setColorShadeSlate();
      });

      $('#setColorShadeGray').once('clicked').click(function () {
        setColorShadeGray();
      });

      $('#setColorShadeZinc').once('clicked').click(function () {
        setColorShadeZinc();
      });

      $('#setColorShadeNeutral').once('clicked').click(function () {
        setColorShadeNeutral();
      });

      $('#setColorShadeStone').once('clicked').click(function () {
        setColorShadeStone();
      });

      $(document).ready(function () {
        let colorShade = window.localStorage.getItem('ohano.color_shade');
        if ($('body').hasClass('authenticated')) {
          return;
        }

        switch (colorShade) {
          case 'slate':
            setColorShadeSlate();
            break;

          case 'gray':
            setColorShadeGray();
            break;

          case 'zinc':
            setColorShadeZinc();
            break;

          case 'neutral':
            setColorShadeNeutral();
            break;

          case 'stone':
            setColorShadeStone();
            break;
        }
      });
    }
  }
})(jQuery, Drupal, drupalSettings);
