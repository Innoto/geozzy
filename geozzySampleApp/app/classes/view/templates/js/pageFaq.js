var geozzy = geozzy || {};

$(document).ready(function(){
  $(window).scroll(sticky_SecPageFaq);
  sticky_init_SecPageFaq();
/*
  $('#accordionFaq').on('hidden.bs.collapse', function () {
    $(window).trigger('resize.px.parallax');
  })
*/
/*
  $('#accordionFaq').on('shown.bs.collapse', function (e) {
    // Get clicked element that initiated the collapse...
    clicked = $(document).find("[href='#" + $(e.target).attr('id') + "']");
    $('html, body').animate({
        scrollTop: clicked.offset().top-100
    }, 1000);
  });
*/
  $('#accordionFaq').collapse({
    toggle: false
  });
});

function sticky_SecPageFaq(  ) {
    var window_top = $(window).scrollTop();
    var div_top = $('.gzz-sticky-top').offset().top;
    var end_sec = $('.endSecPageFaq').offset().top;
    var sticky_height = $('.gzz_sticky').outerHeight();

    if ((window_top)+ 90 > div_top) {
        $('.gzz_sticky').addClass('stick');
        $('.gzz-sticky-sticky').height(sticky_height);

        if((window_top)+90+sticky_height > end_sec){
          $('.gzz_sticky').removeClass('stick');
          $('.gzz-sticky-sticky').height(0);
        }
    }
    else {
        $('.gzz_sticky').removeClass('stick');
        $('.gzz-sticky-sticky').height(0);
    }
}
function sticky_init_SecPageFaq(  ) {
    $('.gzz_sticky').before('<div class="gzz-sticky-top"></div>');
    sticky_SecPageFaq();
}
