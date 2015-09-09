
var app = app || {};


$(document).ready(function(){
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
    var rtypeParent = $('#rTypeIdName').val();
    //PARAMS( URL - ID - TITLE )
    app.mainView.loadAjaxContentModal('/admin/collection/create/'+rtypeParent, 'createCollectionsModal', 'Create Collection');
  });

  $('#resourceMultimediaGalleries').multiList({
    itemActions : [
      { 'id': 'edit', 'html': '<i class="fa fa-pencil-square-o"></i>', 'action': editMultimediaGallery }
    ],
    icon: '<i class="fa fa-arrows"></i>'
  });

  $('#resourceAddMultimediaGallery').on('click', function(){
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
      zoom: zoom,
      scrollwheel: false
    };
    var resourceMap = new google.maps.Map(document.getElementById('resourceLocationMap'), mapOptions);

    // add marker

    var my_marker = {
      url: media+'/module/admin/img/geozzy_marker.png',
      // This marker is 20 pixels wide by 36 pixels high.
      size: new google.maps.Size(30, 36),
      // The origin for this image is (0, 0).
      origin: new google.maps.Point(0, 0),
      // The anchor for this image is the base of the flagpole at (0, 36).
      anchor: new google.maps.Point(13, 36)
    };

    var resourceMarker = new google.maps.Marker({
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

  if(data.multimedia === '1'){
    //Multimedia galery
    if( $('#resourceMultimediaGalleries option[value='+data.id+']').length > 0 ){
      $('#resourceMultimediaGalleries option[value='+data.id+']').text(data.title);
      $('#editMultimediaGalleryModal').modal('hide');
    }else{
      $('#resourceMultimediaGalleries').append('<option selected="selected" value="'+data.id+'">'+data.title+'</option>');
      $('#createMultimediaGalleryModal').modal('hide');
    }
    $('#resourceMultimediaGalleries').trigger('change');
    //End multimedia
  }else{
    //Collection
    if( $('#resourceCollections option[value='+data.id+']').length > 0 ){
      $('#resourceCollections option[value='+data.id+']').text(data.title);
      $('#editCollectionsModal').modal('hide');
    }else{
      $('#resourceCollections').append('<option selected="selected" value="'+data.id+'">'+data.title+'</option>');
      $('#createCollectionsModal').modal('hide');
    }
    $('#resourceCollections').trigger('change');
    //End collection
  }

}

function editCollection(e){
  var rtypeParent = $('#rTypeIdName').val();
  app.mainView.loadAjaxContentModal('/admin/collection/edit/'+e.value+'/'+rtypeParent, 'editCollectionsModal', 'Edit Collection');
}
function editMultimediaGallery(e){
  app.mainView.loadAjaxContentModal('/admin/multimedia/edit/'+e.value, 'editMultimediaGalleryModal', 'Edit Multimedia Gallery');
}
