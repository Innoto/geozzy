$(function() {


  $('#side-menu').metisMenu();
  resizingPageWrapper();

  $(window).bind("load resize", function() {
    resizingPageWrapper();
  })

});




function resizingPageWrapper (){

  topOffset = 51;
  width = (this.window.innerWidth > 0) ? this.window.innerWidth : this.screen.width;
  if (width < 768) {
    $('div.navbar-collapse').addClass('collapse')
    topOffset = 100; // 2-row-menu
  } else {
    $('div.navbar-collapse').removeClass('collapse')
  }

  height = (this.window.innerHeight > 0) ? this.window.innerHeight : this.screen.height;
  height = height - topOffset;
  if (height < 1) {
    height = 1;
  }

  if (height > topOffset) {
    $("#page-wrapper").css("min-height", (height) + "px");
  }
}