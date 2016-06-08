  $(document).ready(function(){
    var url = window.location.href.split('#');
    if (url[1]=='app'){
      $('.audioguide audio').attr('autoplay', true);
    }
  });
