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
      url: "/admin/taxonomygroup/term/send",
      data: { idName: idName, group: taxGroup }
    }).done(function( res ) {
      if( is_object(res) ){
        $('#listTerms').append( htmlToList(res) );
      }
    });
  }
}

function htmlToList(res){
  return  '<li class="list-group-item">'+
    '<i class="fa fa-tag"></i> '+res->idName+
    '<a class="btn btn-default btn-success" href="#" role="button"><i class="fa fa-pencil"></i></a>'+
    '<a class="btn btn-default btn-danger" href="#" role="button"><i class="fa fa-close"></i></a>'+
  '</li>';
}