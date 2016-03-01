jQuery(document).ready(function ($) {
  var $bodyClass = $('.et_divi_100_custom_hamburger_menu');

  // :before isn't apart of the dom, so we're unable to get the true accent color.
  // var accentColor = $(".mobile_menu_bar:before").css("color");
  var accentColor = $(".mobile_menu_bar").css("color");

  // Checks if body class exists.
  if (!$bodyClass.length) {
    // Finds .mobile_menu_bar_toggle class
    // Adds .et_divi_100_custom_hamburger_menu__icon class
    // Add 3 divs to html
    // Gets theme colors
    var bodyColor = $("body").css("color");

    $(".mobile_menu_bar")
      .addClass("et_divi_100_custom_hamburger_menu__icon")
      .html('<div></div><div></div><div></div>');


    // Append colors style block
    $('.et_divi_100_custom_hamburger_menu__icon div, span.mobile_menu_bar.et_toggle_fullscreen_menu.et_divi_100_custom_hamburger_menu__icon.et_divi_100_custom_hamburger_menu__icon--toggled div').css('color', accentColor);

    $('<style> .et_divi_100_custom_hamburger_menu--type-2 .et_divi_100_custom_hamburger_menu__icon div:before, .et_divi_100_custom_hamburger_menu--type-2 .et_divi_100_custom_hamburger_menu__icon div:after, .et_divi_100_custom_hamburger_menu--type-3 .et_divi_100_custom_hamburger_menu__icon div:before, .et_divi_100_custom_hamburger_menu--type-3 .et_divi_100_custom_hamburger_menu__icon div:after {background: ' + bodyColor + '} </style>').appendTo('body');

    // Menu click event
    $('.et_divi_100_custom_hamburger_menu__icon').on('click', function (e) {
      e.preventDefault();
      $('.et_divi_100_custom_hamburger_menu__icon').toggleClass('et_divi_100_custom_hamburger_menu__icon--toggled');
    });

    $('.toggle_all').on('click', function (e) {
      e.preventDefault();
      $('.et_divi_100_custom_hamburger_menu__icon').toggleClass('et_divi_100_custom_hamburger_menu__icon--toggled');
    });
  }
});