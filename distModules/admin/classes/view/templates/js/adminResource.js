
cogumelo.log('Cargamos adminResource.js');


var app = app || {};

var resourceMap = false;
var resourceMarker = false;


$(document).ready( function() {
  cogumelo.log('Inicializamos adminResource.js');

  var els = $('.switchery');
  els.each(function( index )  {
    var switchery = new Switchery( this, { color : '#58ba81', secondaryColor : '#FF6A4B'} );
  });

  /* Anclas a paneles */
   $('.panelBlocktoLink').each(function(){
     var idBlock = $(this).attr('id');
     var panelTag = '<li class="tagBlock" data-idBlockPanel="'+idBlock+'">'+idBlock.replace(/_/g," ")+'</li>';
     $('.panelIdTags').append(panelTag);
   });

   $('.tagBlock').each(function(){
       $(this).click(function(e){
         scroll_to_anchor($(this).attr('data-idBlockPanel'));
       });
   });

  bindResAdminForm();
  initializeMaps( );
});

function scroll_to_anchor(anchor_id){
    var tag = $("#"+anchor_id+"");
    $('html,body').animate({scrollTop: tag.offset().top-130},'slow');
}

function bindResAdminForm(){


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
      { 'id': 'edit', 'html': '<i class="far fa-edit fa-xs fa-fw"></i>', 'action': editCollection }
    ],
    icon: '<i class="fas fa-arrows-alt fa-fw"></i>',
    placeholder: __('Select options')
  });

  $('select.gzzMultiList').multiMultiList({
    orientation: 'horizontal',
    icon: '<i class="fas fa-arrows-alt fa-fw"></i>',
    placeholder: __('Select options')
  });

  $('select.gzzMultiListImg').multiMultiList({
    orientation: 'Horizontal',
    itemImage: true,
    icon: '<i class="fas fa-arrows-alt fa-fw"></i>',
    placeholder: __('Add existing resources')
  });

  $('select.gzzSelect2').select2();
  $('select.gzzSelect2tags').select2({tags:true});
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
