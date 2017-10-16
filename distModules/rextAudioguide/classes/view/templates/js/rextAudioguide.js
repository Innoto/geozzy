  $(document).ready(function(){
    var all_url = window.location.href.split('?');
    var url = all_url[1].split('=');
    if (url[0]=="autoplay" && url[1]=="true"){
      $('.audioguideBlock audio').attr('autoplay', true)
    }
  });
