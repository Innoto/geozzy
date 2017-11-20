  $(document).ready(function(){
    /*$('video, audio').mediaelementplayer({
      // Do not forget to put a final slash (/)
      pluginPath: 'https://cdnjs.com/libraries/mediaelement/',
      defaultAudioWidth: '250',
      shimScriptAccess: 'always'
    });*/
    var all_url = window.location.href.split('?');
    if(all_url[1]){
      var url = all_url[1].split('=');
      if (url[0]=="autoplay" && url[1]=="true"){
        $('.audioguideBlock audio').attr('autoplay', true)
      }
    }
  });
