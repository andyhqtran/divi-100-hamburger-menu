jQuery(document).ready(function ($) {

  // Appends current search to body
  // Replaces .et_search_outer with .et_custom_search
  // Removes unnessary div
  $(".mobile_menu_bar_toggle")
    .addClass("et_divi_100_custom_hamburger_menu")
    .html('<div></div><div></div><div></div>');


  $('.et_divi_100_custom_hamburger_menu').on('click', function (e) {
    e.preventDefault();
    $(this).toggleClass('et_divi_100_custom_hamburger_menu--toggled');
    return console.log('Success: ' + $(this).attr('class'));
  });
  $('.toggle_all').on('click', function (e) {
    e.preventDefault();
    $('.et_divi_100_custom_hamburger_menu').toggleClass('et_divi_100_custom_hamburger_menu--toggled');
    return console.log('Successful');
  });
});