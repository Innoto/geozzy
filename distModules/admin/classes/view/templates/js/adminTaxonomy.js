$(document).ready(function(){

  /*BINDS*/
  $('#newTaxTermShow').on( "click", function() {
    showAddTerm();
  });
  $('#newTaxTermSave').on( "click", function() {
    var idName = $('#newTaxTermName').val();
    saveTerm( idName, idTax );
  });

});

function showAddTerm(){
  $('.newTaxTermContainer').toggle();

}

function saveTerm( idName, taxGroup ){
  if( !idName || idName === ""){
    res = false;
  }
  else {
    $.ajax({
      method: "POST",
      url: "/admin/taxonomygroup/"+taxGroup+"/term/create",
      data: { idName: idName, taxGroup: taxGroup }
    }).done(function( msg ) {
      alert( "Data Saved: " + msg );
    });
  }
}