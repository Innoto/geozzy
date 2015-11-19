
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



});
