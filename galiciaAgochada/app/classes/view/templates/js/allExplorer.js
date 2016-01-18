$(document).ready(function(){
  
  // autodetección de idioma
  path = window.location.pathname.split('/');

  has_lang = false;
  $(langAvailableIds).each(function(i,e){
    if (path[1]===e){
      has_lang = true;
    }
  });

  if(!has_lang){ // url sen idioma
    if (navigator.appName == 'Netscape' || 'Microsoft Internet Explorer' || 'Opera'){
      var idioma = navigator.language;
    }
    else{
      var idioma = navigator.browserLanguage;
    }
    lang_array = idioma.split('-');
    window.location = lang_array[0] + window.location.pathname;
  }

});
