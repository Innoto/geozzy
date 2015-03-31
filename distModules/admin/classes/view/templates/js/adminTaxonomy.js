$(document).ready(function(){
  statusTerm('list');
  bindsTerm();
});

function bindsTerm(){
  /*BINDS*/
  $('#newTaxTermShow').on( "click", function() {
    showAddTerm();
  });
  $('#newTaxTermSave').on( "click", function() {
    var idName = $('#newTaxTermName').val();
    saveTerm( idName, idTax );
  });

  $('.btnDeleteTerm').on("click", function(){
    if( confirm("¿Estas seguro que quieres eliminar este término?") ){
      var termContainer = $(this).parent().parent().parent();
      var termId = termContainer.attr('termId');
      deleteTerm( termId , this );
    }
  });
  $('.btnEditTerm').on("click", function(){
    var termContainer = $(this).parent().parent().parent();
    var termId = termContainer.attr('termId');
    statusTerm( 'edit' , termContainer );
  });
  $('.btnSaveTerm').on("click", function(){
    var termContainer = $(this).parent().parent().parent();
    var termId = termContainer.attr('termId');
    if( confirm("¿Estas seguro que quieres guardar este término?") ){
      statusTerm( 'save' , termContainer );
    }else{
      statusTerm( 'list' , termContainer );
    }
  });
  $('.btnCancelTerm').on("click", function(){
    var termContainer = $(this).parent().parent().parent();
    var termId = termContainer.attr('termId');
    statusTerm( 'list' , termContainer );
  });
}

function showAddTerm(){
  $('#newTaxTermName').val('');
  $('.newTaxTermContainer').toggle();
}

function statusTerm( status, termO ){

  switch(status){
    case 'save':
    case 'list':
      if(!termO){
        $('.btnSaveTerm, .btnCancelTerm, .editTermContainer').hide();
        $('.btnEditTerm, .btnDeleteTerm, .infoTerm').show();

      }else{
        termO.find('.btnSaveTerm').hide();
        termO.find('.btnCancelTerm').hide();
        termO.find('.editTermContainer').hide();
        termO.find('.btnEditTerm').show();
        termO.find('.btnDeleteTerm').show();
        termO.find('.infoTerm').show();
      }

    break;
    case 'edit':
      if(!termO){
        $('.btnEditTerm, .btnDeleteTerm, .infoTerm').hide();
        $('.btnSaveTerm, .btnCancelTerm, .editTermContainer').show();
      }else{
        termO.find('.btnEditTerm').hide();
        termO.find('.btnDeleteTerm').hide();
        termO.find('.infoTerm').hide();

        termO.find('.btnSaveTerm').show();
        termO.find('.btnCancelTerm').show();
        termO.find('.editTermContainer').show();
      }
    break;
  }

}

function saveTerm( idName, taxGroup ){
  if( !idName || idName === ""){
    res = false;
  }
  else {
    $.ajax({
      method: "POST",
      url: "/admin/taxonomygroup/term/send",
      data: { idName: idName, taxgroup: taxGroup }
    }).done(function( res ) {
      if( typeof(res) == 'object' ){
        $('#listTerms').append( htmlToList(res) );
        showAddTerm();
      }
    });
  }
}
function deleteTerm( id , elem ){
   $.ajax({
      method: "POST",
      url: "/admin/taxonomygroup/term/dlt",
      data: { id: id, taxgroup: idTax }
    }).always(function( res ) {
        $(elem).find('i').addClass('fa-spinner fa-spin');

    }).done(function( res ) {
      if(res) {
        $(elem).parent().parent().parent().remove();
        $(elem).find('i').removeClass('fa-spinner fa-spin');
      }
    });
}


function htmlToList(res){
  return  '<li class="list-group-item" termId="'+res.id+'">'+
            '<div class="row">'+
              '<div class="col-md-9">'+res.idName+'</div>'+
              '<div class="col-md-3">'+
                '<button class="btnSaveTerm btn btn-default btn-success"><i class="fa fa-check"></i></button>'+
                '<button class="btnEditTerm btn btn-default btn-info"><i class="fa fa-pencil"></i></button>'+
                '<button class="btnDeleteTerm btn btn-default btn-danger"><i class="fa fa-trash"></i></button>'+
                '<button class="btnCancelTerm btn btn-default btn-danger"><i class="fa fa-close"></i></button>'+
              '</div>'+
            '</div>'+
          '</li>';
}


