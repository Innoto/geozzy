$(document).ready(function(){

  var all_url = window.location.href.split('?');
  if(all_url[1]){
    var url = all_url[1].split('=');
    if (url[0]=="autoplay" && url[1]=="true"){
      $('.audioguideBlock audio').attr('autoplay', true)
    }
  }

  $('.audioPlayer').html('<audio id="myPlayer" controls><source src="{$cogumelo.publicConf.mediaHost}cgmlformfilews/{$rExt.data.audioFile.id}/{$rExt.data.audioFile.originalName}" type="{$rExt.data.audioFile.type}">'+
       'Your browser does not support the audio element.</audio>');



});
