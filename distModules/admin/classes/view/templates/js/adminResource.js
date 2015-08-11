
var app = app || {};


$(window).ready(function(){
  var els = $('.switchery');
  els.each(function( index )  {
    var switchery = new Switchery( this, { color : '#58ba81', secondaryColor : '#FF6A4B'} );
  });

  bindResourceForm();
});

function bindResourceForm(){
  $('#resourceCollections').multiList({
    itemActions : [
      { 'id': 'edit', 'html': '<i class="fa fa-pencil-square-o"></i>', 'action': editCollection }
    ],
    icon: '<i class="fa fa-arrows"></i>'
  });

  $('#resourceAddCollection').on('click', function(){
    //PARAMS( URL - ID - TITLE )
    app.mainView.loadAjaxContentModal('/admin/collection/create', 'createCollectionsModal', 'Create Collection');
  });

  $('#resourceMultimediaGalleries').multiList({
    itemActions : [
      { 'id': 'edit', 'html': '<i class="fa fa-pencil-square-o"></i>', 'action': editMultiMediaGallery }
    ],
    icon: '<i class="fa fa-arrows"></i>'
  });

  $('#resourceAddMultimediaGalleries').on('click', function(){
    //PARAMS( URL - ID - TITLE )
    app.mainView.loadAjaxContentModal('/admin/multimedia/create', 'createMultimediaGalleryModal', 'Create Multimedia Gallery');
  });




  $('select.cgmMForm-field-rExtAccommodation_accommodationType').multiList({
    orientation: 'horizontal'
  });
  $('select.cgmMForm-field-rExtAccommodation_accommodationServices').multiList({
    orientation: 'horizontal'
  });
  $('select.cgmMForm-field-rExtAccommodation_accommodationFacilities').multiList({
    orientation: 'horizontal'
  });

  $('select.cgmMForm-field-rextEatAndDrink_eatanddrinkSpecialities').select2();



  // Location Map
  if(  $("input[name='locLat']").length &&  $("input[name='locLon']").length ) {
    var latInput = $("input[name='locLat']");
    var lonInput = $("input[name='locLon']");
    var defaultZoom = $("input[name='defaultZoom']");
    var locationContainer = latInput.parent().parent();

    latInput.parent().hide();
    lonInput.parent().hide();
    defaultZoom.parent().hide();

    locationContainer.append('<div id="resourceLocationMap"></div>');

    var latValue = 0;
    var lonValue = 0;
    var zoom = 1;

    if( latInput.val() != '' && latInput.val() != '') {
      latValue = parseFloat( latInput.val() );
      lonValue = parseFloat( lonInput.val() );
      zoom = parseInt( defaultZoom.val() );
    }

    // gmaps init
    var mapOptions = {
      center: { lat: latValue, lng: lonValue },
      zoom: zoom
    };
    var resourceMap = new google.maps.Map(document.getElementById('resourceLocationMap'), mapOptions);

    // add marker
    var resourceMarker = new google.maps.Marker({
      position: new google.maps.LatLng( latValue, lonValue ),
      map: false,
      title: 'Resource location',
      draggable: true

    });

    // Draggend event
    google.maps.event.addListener( resourceMarker,'dragend',function(e) {
      latInput.val( resourceMarker.position.lat() );
      lonInput.val( resourceMarker.position.lng() );
    });

    // Click map event
    google.maps.event.addListener(resourceMap, 'click', function(e) {
      resourceMarker.setPosition( e.latLng )
      resourceMarker.setMap( resourceMap );

      latInput.val( resourceMarker.position.lat() );
      lonInput.val( resourceMarker.position.lng() );

      defaultZoom.val( resourceMap.getZoom() );
    });

    // map zoom changed
    google.maps.event.addListener(resourceMap, 'zoom_changed', function(e) {
      defaultZoom.val( resourceMap.getZoom() );
    });


    if( latInput.val() != '') {
      resourceMarker.setMap( resourceMap);
    }

  }

}

function successCollectionForm( data ){

  if( $('#resourceCollections option[value='+data.id+']').length > 0 ){
    $('#resourceCollections option[value='+data.id+']').text(data.title);
    $('#editCollectionsModal').modal('hide');
  }else{
    $('#resourceCollections').append('<option selected="selected" value="'+data.id+'">'+data.title+'</option>');
    $('#createCollectionsModal').modal('hide');
  }
  $('#resourceCollections').trigger('change');
}

function editCollection(e){
  app.mainView.loadAjaxContentModal('/admin/collection/edit/'+e.value, 'editCollectionsModal', 'Edit Collection');
}
function editMultiMediaGallery(e){
  app.mainView.loadAjaxContentModal('/admin/multimedia/edit/'+e.value, 'editMultimediaModal', 'Edit Multimedia Gallery');
}
