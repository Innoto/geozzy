  $(document).ready(function(){
    alert(window.location.href)
    var all_url = window.location.href.split('?');
    var url = all_url[1].split('=');
    alert(url[0])
    alert(url[1])
    if (url[0]=="autoplay" && url[1]=="true"){
      alert('entra')
      $('.audioguia audio').attr('autoplay', true);
    }
  });
