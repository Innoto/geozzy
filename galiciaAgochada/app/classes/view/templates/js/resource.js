var geozzy = geozzy || {};
firstLoad = firstLoadAll = true;

$(document).ready(function(){

  $('.resource .directions .title').click(function(){
    //this.find('i').toggle();
    $('.directions .indications').toggle();
    $('.rExtMapDirections .mapRoute').toggle();
  });
  $('.rExtMapDirections .mapRoute').hide();

  $('.rtypeHotel .reservationSec .reservationBox .reservationBtb .showReservation').click(function(){
    $('.rtypeHotel .reservationSec .reservationBox').hide();
    $('.rtypeHotel .reservationSec .reservationData').show();
    $('.rtypeHotel .reservationSec .reservationData .reservationBtb .showAverageRate').click(function(){
      $('.rtypeHotel .reservationSec .reservationBox').show();
      $('.rtypeHotel .reservationSec .reservationData').hide();
    })
  });

  $('.elementShare').hover(
    function(){ //mouseenter
      $('.share').css('visibility','hidden');
      $('.share-open').show();
    },
    function(){ //mouseleave
      $('.share-open').hide();
      $('.share').css('visibility','visible');
    }
  );

  if (typeof idGallery !== 'undefined'){

    $.each(idGallery, function(i, elm){
      if ($('#multimediaGallery_'+elm)){
        $('#multimediaGallery_'+elm).unitegallery({
          gallery_theme: "tiles",
       		tiles_type: "justified"
        });

        $('.multimediaSec #more_'+elm).bind('click', function(){
          var colMulti = getCollection(elm);
          if (colMulti.firstLoad){
            $('#multimediaAllGallery_'+elm).append(colMulti.html);
            $('#multimediaAllGallery_'+elm).unitegallery({
              gallery_theme: "tiles",
              tiles_type: "justified"
            });
            colMulti.firstLoad = false;
          }
          else{
            $("#multimediaAllGallery_"+elm).css('display','block');
          }
          showMoreMultimedia(elm);

          $('.multimediaSec #less_'+elm).bind('click', function(){
            showLessMultimedia(idGallery);
          });
        });
      }
    });
  }

  $('.owl-carousel').owlCarousel({
    margin:10,
    nav:true,
    responsive:{
      0:{
          items:1
      },
      767:{
          items:3
      },
      1200:{
          items:4
      }
    }
  });

  $('.owl-carousel .owl-nav .owl-prev').html('<i class="fa fa-angle-left"></i>');
  $('.owl-carousel .owl-nav .owl-next').html('<i class="fa fa-angle-right"></i>');

  initEffectNavs();

});

function getCollection(id){
  return $.grep(multimedia, function(e){
    return e.id == id;
  })[0];
}


function initEffectNavs(){
  $('a.page-scroll').bind('click', function scrollSuave(event) {
    var hrefText = $(this).attr('href');
    if( hrefText.indexOf( '#' ) === 0 ) {
      var $anchor = $( hrefText );
      if( $anchor.length > 0) {
        $('html, body').stop().animate({
            scrollTop: $anchor.offset().top
        }, 1500, 'easeInOutExpo');
        event.preventDefault();
      }
    }
  });
}


// Show all the elements
function showMoreMultimedia(idGallery){
  $('.multimediaSec #more_'+idGallery).hide();
  $('.multimediaSec #less_'+idGallery).show();
  $("#multimediaGallery_"+idGallery).css('display','none');
}

// Show the initially loaded elements
function showLessMultimedia(idGallery){
  $('.multimediaSec #more_'+idGallery).show();
  $('.multimediaSec #less_'+idGallery).hide();
  $("#multimediaGallery_"+idGallery).css('display','block');
  $('#multimediaAllGallery_'+idGallery).css('display','none');
}
