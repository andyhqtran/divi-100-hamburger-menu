jQuery(document).ready(function ($) {

  // :before isn't apart of the dom, so unable to get the accent color.
  // var accentColor = $(".mobile_menu_bar:before").css("color");

  var $bodyClass = $('.et_divi_100_custom_hamburger_menu'),
    $accentColor = $(".mobile_menu_bar").css("color"),
    $bodyColor = $("body").css("color");

  // Checks if body class exists.
  if ($bodyClass.length) {
    var $iconName = 'et_divi_100_custom_hamburger_menu__icon',
      $toggledName = $iconName + '--toggled';

    // Finds .mobile_menu_bar_toggle class
    // Adds .et_divi_100_custom_hamburger_menu__icon class
    // Add 3 divs to html
    // Gets theme colors

    $(".mobile_menu_bar")
      .addClass($iconName)
      .html('<div></div><div></div><div></div>');

    // Append colors style block
    $('.et_divi_100_custom_hamburger_menu__icon div, span.mobile_menu_bar.et_toggle_fullscreen_menu.et_divi_100_custom_hamburger_menu__icon.et_divi_100_custom_hamburger_menu__icon--toggled div').css('color', $accentColor);

    $('<style> .et_divi_100_custom_hamburger_menu--type-2 .et_divi_100_custom_hamburger_menu__icon div:before, .et_divi_100_custom_hamburger_menu--type-2 .et_divi_100_custom_hamburger_menu__icon div:after, .et_divi_100_custom_hamburger_menu--type-3 .et_divi_100_custom_hamburger_menu__icon div:before, .et_divi_100_custom_hamburger_menu--type-3 .et_divi_100_custom_hamburger_menu__icon div:after {background: ' + $bodyColor + '} </style>').appendTo('body');

    // Menu click event
    $('.' + $iconName).on('click', function (e) {
      e.preventDefault();
      $('.' + $iconName).toggleClass($toggledName);
    });

    $('.toggle_all').on('click', function (e) {
      e.preventDefault();
      $('.' + $iconName).toggleClass($toggledName);
    });
  }
});