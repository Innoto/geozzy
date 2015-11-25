
$(document).ready(function(){

  $('.resource .directions .title').click(function(){
    $('.directions .indications').toggle();
  });

  $('.rtypeHotel .reservationSec .reservationBox .reservationBtb .showReservation').click(function(){
    $('.rtypeHotel .reservationSec .reservationBox').hide();
    $('.rtypeHotel .reservationSec .reservationData').show();
    $('.rtypeHotel .reservationSec .reservationData .reservationBtb .showAverageRate').click(function(){
      $('.rtypeHotel .reservationSec .reservationBox').show();
      $('.rtypeHotel .reservationSec .reservationData').hide();
    })
  })

  if ($("#imageGallery")){
    $("#imageGallery").unitegallery({
      gallery_theme: "tiles",
  		tiles_type: "justified"
    });
  }

  if ($("#collectionsGallery")){
    $("#collectionsGallery").unitegallery({
      gallery_theme: "tiles",
  	 	tiles_type: "justified"
    });
  }

  $('.collectionSec .more').bind('click', function(){
    $('#collectionsAllGallery').append(resources);
    $("#collectionsGallery").css('display','none');
    $('#collectionsAllGallery').unitegallery({
      gallery_theme: "tiles",
  	 	tiles_type: "justified"
    });
  });


});
