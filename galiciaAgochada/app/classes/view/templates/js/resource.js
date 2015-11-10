
$(document).ready(function(){

  $('.rtypeHotel .directions .title').click(function(){
    $('.directions .indications').toggle();
  });

  $('.rtypeHotel .reservationSec .reservationBox div.reservationBtb').click(function(){
    $('.rtypeHotel .reservationSec .reservationBox').hide();
    $('.rtypeHotel .reservationSec .reservationData').show();
  })

});
