$(document).ready(function(){


  setTimeout(function(){ // non pode colisionar cos ready
    if( typeof audioGuideTagSource != 'undefined' ) {
      $('.audioPlayer').html(audioGuideTagSource);
    }

    var all_url = window.location.href.split('?');
    if(all_url[1]){
      var url = all_url[1].split('=');
      if (url[0]=="autoplay" && url[1]=="true"){
        $('.audioguideBlock audio').attr('autoplay', true);
      }
    }
  }, 1000);


});
