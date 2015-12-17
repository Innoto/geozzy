/*
(function($) {

  $.fn.selectZonaMap = function( options ){
    var that = this;
    var selector = this;
    var optSelected = "";

    var defaults = {

    };
    var settings = $.extend( {}, defaults, options );

    that.initOptionsValues = function (){

    }
  ));
});
*/


$(document).ready(function(){
  initZonaMap();
});

function zonaMapMouseOver(idNameTerm){
  $('#zonaGalMap_title').html(idNameTerm);
  $('.zonaGal_'+idNameTerm).show();
}

function zonaMapClick(idNameTerm){
  alert(idNameTerm);
}

function zonaMapMouseOut(){
  $('.zonaGal').hide();
}

function initZonaMap(){
  zonaMapMouseOut();
}
