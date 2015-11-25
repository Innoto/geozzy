firstLoad = firstLoadAll = true;

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

    if (firstLoad){
      $('#collectionsAllGallery').append(recursos);
      $('#collectionsAllGallery').unitegallery({
        gallery_theme: "tiles",
    	 	tiles_type: "justified"
      });
      firstLoad = false;
    }
    else{
      $("#collectionsAllGallery").css('display','block');
    }
    showMore();

    $('.collectionSec .less').bind('click', function(){
      showLess();
    });
  });


});

// Show all the elements
function showMore(){
  $('.collectionSec .more').hide();
  $('.collectionSec .less').show();
  $("#collectionsGallery").css('display','none');
}

// Show the initially loaded elements
function showLess(){
  $('.collectionSec .more').show();
  $('.collectionSec .less').hide();
  $("#collectionsGallery").css('display','block');
  $('#collectionsAllGallery').css('display','none');
}
