jQuery(document).ready(function ($) {
  /**
   * Checks if body class exists
   */
  if ($('.et_divi_100_custom_hamburger_menu').length > 0) {
    var iconName = 'et_divi_100_custom_hamburger_menu__icon',
      toggledName = iconName + '--toggled';

    /**
     * Appends hamburger bars to menu
     */
    $(".mobile_menu_bar")
      .addClass(iconName)
      .html('<div></div><div></div><div></div>');

    /**
     * Handle click event
     */
    $('.' + iconName).on('click', function (e) {
      e.preventDefault();
      $('.' + iconName).toggleClass(toggledName);
    });
  }
});