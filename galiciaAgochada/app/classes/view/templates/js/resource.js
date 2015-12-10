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

  if ($("#multimediaGallery")){
     $("#multimediaGallery").unitegallery({
       gallery_theme: "tiles",
   		tiles_type: "justified"
     });
   }

  $('.multimediaSec .more').bind('click', function(){

    if (firstLoad){
      $('#multimediaAllGallery').append(multimedia);
      $('#multimediaAllGallery').unitegallery({
        gallery_theme: "tiles",
        tiles_type: "justified"
      });
      firstLoad = false;
    }
    else{
      $("#multimediaAllGallery").css('display','block');
    }
    showMoreMultimedia();

    $('.multimediaSec .less').bind('click', function(){
      showLessMultimedia();
    });
  });
/*
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
    showMoreCollection();

    $('.collectionSec .less').bind('click', function(){
      showLessCollection();
    });
  });

*/
});

// Show all the elements
function showMoreMultimedia(){
  $('.multimediaSec .more').hide();
  $('.multimediaSec .less').show();
  $("#multimediaGallery").css('display','none');
}

// Show the initially loaded elements
function showLessMultimedia(){
  $('.multimediaSec .more').show();
  $('.multimediaSec .less').hide();
  $("#multimediaGallery").css('display','block');
  $('#multimediaAllGallery').css('display','none');
}


// Show all the elements
function showMoreCollection(){
  $('.collectionSec .more').hide();
  $('.collectionSec .less').show();
  $("#collectionsGallery").css('display','none');
}

// Show the initially loaded elements
function showLessCollection(){
  $('.collectionSec .more').show();
  $('.collectionSec .less').hide();
  $("#collectionsGallery").css('display','block');
  $('#collectionsAllGallery').css('display','none');
}
