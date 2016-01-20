jQuery(document).ready(function ($) {

  // Finds .mobile_menu_bar_toggle class
  // Adds .et_divi_100_custom_hamburger_menu class
  // Add 3 divs to html

  $(".mobile_menu_bar_toggle")
    .addClass("et_divi_100_custom_hamburger_menu")
    .html('<div></div><div></div><div></div>');

  // Gets theme Accent Color
  var accentColor = $("a").css("color");

  // Append accent color style block
  $('<style> .et_divi_100_custom_hamburger_menu div {background: ' + accentColor + '} .et_divi_100_custom_hamburger_menu--type-2 .et_divi_100_custom_hamburger_menu div, .et_divi_100_custom_hamburger_menu--type-3 .et_divi_100_custom_hamburger_menu div {background: 0; } .et_divi_100_custom_hamburger_menu--type-2 .et_divi_100_custom_hamburger_menu div:before, .et_divi_100_custom_hamburger_menu--type-2 .et_divi_100_custom_hamburger_menu div:after, .et_divi_100_custom_hamburger_menu--type-3 .et_divi_100_custom_hamburger_menu div:before, .et_divi_100_custom_hamburger_menu--type-3 .et_divi_100_custom_hamburger_menu div:after {background: ' + accentColor + '} </style>').appendTo('body');

  // Menu click event
  $('.et_divi_100_custom_hamburger_menu').on('click', function (e) {
    e.preventDefault();
    $(this).toggleClass('et_divi_100_custom_hamburger_menu--toggled');
  });

  $('.toggle_all').on('click', function (e) {
    e.preventDefault();
    $('.et_divi_100_custom_hamburger_menu').toggleClass('et_divi_100_custom_hamburger_menu--toggled');
  });
});