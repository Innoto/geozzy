$(document).ready(function(){
  zonaGalMapMouseOut();
});

function zonaGalMapMouseOver(idNameTerm){
  $('#zonaGalMap_title').html(idNameTerm);
  $('.zonaGal_'+idNameTerm).show();
}

function zonaGalMapClick(idNameTerm){
  alert(idNameTerm);
}

function zonaGalMapMouseOut(){
  $('.zonaGal').hide();
}
