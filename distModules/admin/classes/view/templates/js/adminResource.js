
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


  // Porto 160810
  if (typeof hasMap!=='undefined')
    initializeMap( formId );
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

function initializeMap( form ){

  formName = '.'+form;

  // Location Map
  if(  $(formName+" input[name='locLat']").length &&  $(formName+" input[name='locLon']").length ) {
    var latInput = $(formName+" input[name='locLat']");
    var lonInput = $(formName+" input[name='locLon']");
    var defaultZoom = $(formName+" input[name='defaultZoom']");

    var mapContainer = $(formName+' .mapContainer');

    // Porto 160810    if( mapContainer.length ) {
    if( mapContainer.length && mapContainer.children('#resourceLocationMap_'+form).length < 1 ) {

      mapContainer.append('<div class="resourceLocationMap" id="resourceLocationMap_'+form+'"></div>');

      // latInput.parent().hide();
      // lonInput.parent().hide();
      // defaultZoom.parent().hide();

      var latValue = 0;
      var lonValue = 0;
      var zoom = 1;
      // España con Canarias
      var latInit = 38;
      var lonInit = -3.7;
      var zoomInit = 4;

      if( latInput.val() !== '' && latInput.val() !== '') {
        latValue = parseFloat( latInput.val() );
        lonValue = parseFloat( lonInput.val() );
        zoom = parseInt( defaultZoom.val() );
        latInit = latValue;
        lonInit = lonValue;
        zoomInit = zoom;
      }

      // gmaps init
      var mapOptions = {
        center: { lat: latInit, lng: lonInit },
        zoom: zoomInit,
        scrollwheel: false
      };
      resourceMap = new google.maps.Map(document.getElementById('resourceLocationMap_'+form), mapOptions);

      // add marker

      var my_marker = {
        url: cogumelo.publicConf.media+'/module/admin/img/geozzy_marker.png',
        // This marker is 20 pixels wide by 36 pixels high.
        size: new google.maps.Size(30, 36),
        // The origin for this image is (0, 0).
        origin: new google.maps.Point(0, 0),
        // The anchor for this image is the base of the flagpole at (0, 36).
        anchor: new google.maps.Point(13, 36)
      };

      resourceMarker = new google.maps.Marker({
        position: new google.maps.LatLng( latValue, lonValue ),
        title: 'Resource location',
        icon: my_marker,
        draggable: true
      });

      // Draggend event
      google.maps.event.addListener( resourceMarker,'dragend',function(e) {
        latInput.val( resourceMarker.position.lat() );
        lonInput.val( resourceMarker.position.lng() );
      });

      // Click map event
      google.maps.event.addListener(resourceMap, 'click', function(e) {
        resourceMarker.setPosition( e.latLng );
        resourceMarker.setMap( resourceMap );
        latInput.val( resourceMarker.position.lat() );
        lonInput.val( resourceMarker.position.lng() );

        defaultZoom.val( resourceMap.getZoom() );
      });

      // map zoom changed
      google.maps.event.addListener(resourceMap, 'zoom_changed', function(e) {
        defaultZoom.val( resourceMap.getZoom() );
      });


      if( latInput.val() !== '') {
        resourceMarker.setMap( resourceMap);
      }

      $(formName+' .locationData .cgmMForm-field').each(function(i,e){
        $(e).change(function(){
          newPos = new google.maps.LatLng( latInput.val(), lonInput.val() );
          resourceMarker.position.lat(latInput.val());
          resourceMarker.position.lng(lonInput.val());
          resourceMap.setZoom(parseInt(defaultZoom.val()));
          resourceMap.setCenter(newPos);
          resourceMarker.setPosition(newPos);
          resourceMarker.setMap( resourceMap );
        });
      });

    } // if( mapContainer.length )
  }
}
