  $(document).ready(function(){
    alert('what?')
    var all_url = window.location.href.split('?');
    alert(all_url[1])
    var url = all_url[1].split('=');
    if (url[0]=="autoplay" && url[1]=="true"){
      $('.audioguia audio').attr('autoplay', true);
    }
  });
