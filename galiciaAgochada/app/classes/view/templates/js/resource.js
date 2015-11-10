
$(document).ready(function(){

  $('.rtypeHotel .directions .title').click(function(){
    $('.directions .indications').toggle();
  });

  $('.rtypeHotel .reservationSec .reservationBox .reservationBtb .showReservation').click(function(){
    $('.rtypeHotel .reservationSec .reservationBox').hide();
    $('.rtypeHotel .reservationSec .reservationData').show();
  })

});
