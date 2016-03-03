jQuery(document).ready(function ($) {

  var $bodyClass = $('.et_divi_100_custom_hamburger_menu'),
    $accentColor = $(".et_mobile_menu").css("border-color"),
    $bodyColor = $("body").css("color");
  console.log($accentColor);
  // Checks if body class exists
  if ($bodyClass.length) {
    var $iconName = 'et_divi_100_custom_hamburger_menu__icon',
      $toggledName = $iconName + '--toggled';

    // Appends icon bars
    $(".mobile_menu_bar")
      .addClass($iconName)
      .html('<div></div><div></div><div></div>');

    // Append colors style block
    $('.et_divi_100_custom_hamburger_menu__icon div, span.mobile_menu_bar.et_toggle_fullscreen_menu.et_divi_100_custom_hamburger_menu__icon.et_divi_100_custom_hamburger_menu__icon--toggled div').css('background', $accentColor);

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