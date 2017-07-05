
console.log('Cargamos adminResource.js');


var app = app || {};

var resourceMap = false;
var resourceMarker = false;


$(document).ready( function() {
  console.log('Inicializamos adminResource.js');

  var els = $('.switchery');
  els.each(function( index )  {
    var switchery = new Switchery( this, { color : '#58ba81', secondaryColor : '#FF6A4B'} );
  });

  bindResourceForm();
  initializeMaps( );

});

function bindResourceForm(){

  $('.resourceAddCollection').on('click', function(){
    var rtypeParent = $('#rTypeIdName').val();

    var data = {};
    data.title = $(this).attr('data-col-title');
    data.colSelect = $(this).attr('data-col-select');
    data.colType = $(this).attr('data-col-type');

    //PARAMS( URL - ID - DATA )
    app.mainView.loadAjaxContentModal('/admin/collection/create/'+rtypeParent, data.colSelect+'Modal', data );
  });


  $('select.resourceCollection').multiMultiList({
    itemActions : [
      { 'id': 'edit', 'html': '<i class="fa fa-pencil-square-o"></i>', 'action': editCollection }
    ],
    icon: '<i class="fa fa-arrows"></i>',
    placeholder: __('Select options')
  });

  $('select.gzzMultiList').multiMultiList({
    orientation: 'horizontal',
    icon: '<i class="fa fa-arrows"></i>',
    placeholder: __('Select options')
  });
  $('select.gzzSelect2').select2();
}


function editCollection( e, selector ){

  var rtypeParent = $('#rTypeIdName').val();

  var data = {};
  data.title = __('Edit Collection');
  data.colSelect = $(selector).attr('id');

  app.mainView.loadAjaxContentModal('/admin/collection/edit/'+e.value+'/'+rtypeParent, data.colSelect+'Modal', data);
}
function successCollectionForm( data ){

  if( $('#'+data.collectionSelect+' option[value='+data.id+']').length > 0 ){
    $('#'+data.collectionSelect+' option[value='+data.id+']').text(data.title);
    $('#'+data.collectionSelect+'Modal').modal('hide');
  }
  else{
    $('#'+data.collectionSelect).append('<option selected="selected" value="'+data.id+'">'+data.title+'</option>');
    $('#'+data.collectionSelect+'Modal').modal('hide');
  }
  $('#'+data.collectionSelect).trigger('change');

}

var resourceFormMaps = [];

function initializeMaps( ) {
  basket.require(
    { url:  cogumelo.publicConf.media + '/module/rextMap/js/rExtMapWidgetForm.js', skipCache:true }
  ).then(function () {
    $('.location').each( function(i,e) {
      resourceFormMaps.push( new geozzy.rExtMapWidgetForm( $(e) ) );
    });
  });
}
