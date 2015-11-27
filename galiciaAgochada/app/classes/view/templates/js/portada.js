$( document ).ready(function(){
  $('.headContent').addClass('transparent');
  $(window).bind("scroll", function(e) {
    if($(this).scrollTop() > 0){
      $('.headContent').removeClass('transparent');
    }else{
      $('.headContent').addClass('transparent');
    }
  });

  $('.owl-carousel').owlCarousel({
    loop: true,
    margin:10,
    nav:true,
    responsive:{
        0:{
            items:1
        },
        600:{
            items:3
        },
        1000:{
            items:4
        }
    }
  });

  $('.owl-carousel .owl-nav .owl-prev').html('<i class="fa fa-angle-left"></i>');
  $('.owl-carousel .owl-nav .owl-next').html('<i class="fa fa-angle-right"></i>');
});
