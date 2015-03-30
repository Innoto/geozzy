$(document).ready(function(){

  /*BINDS*/
  $('#newTaxTermShow').on( "click", function() {
    showAddTerm();
  });
  $('#newTaxTermSave').on( "click", function() {
    var idName = $('#newTaxTermName').val();
    saveTerm( idName, idTax );
  });

  bootbox.confirm( "Hola" , function(){

  });

});

function showAddTerm(){
  $('#newTaxTermName').val('');
  $('.newTaxTermContainer').toggle();
}

function saveTerm( idName, taxGroup ){
  if( !idName || idName === ""){
    res = false;
  }
  else {
    $.ajax({
      method: "POST",
      url: "/admin/taxonomygroup/term/send",
      data: { idName: idName, group: taxGroup }
    }).done(function( res ) {
      if( typeof(res) == 'object' ){
        $('#listTerms').append( htmlToList(res) );
        showAddTerm();
      }
    });
  }
}

function htmlToList(res){
  return  '<li class="list-group-item">'+res.idName+
    '<a class="btn btn-default btn-success" href="#" role="button"><i class="fa fa-pencil"></i></a>'+
    '<a class="btn btn-default btn-danger" href="#" role="button"><i class="fa fa-close"></i></a>'+
  '</li>';
}




function deleteTerm( id ){
   $.ajax({
      method: "POST",
      url: "/admin/taxonomygroup/term/delete",
      data: { id: id, group: idTax }
    }).done(function( res ) {

      console.debug(res);
      /*if( typeof(res) == 'object' ){
        $('#listTerms').append( htmlToList(res) );
        showAddTerm();
      }*/
    });
}