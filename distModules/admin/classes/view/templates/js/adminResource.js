
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

  $('#resourceAddCollection').on('click', function(){
    //PARAMS( URL - ID - TITLE )
    app.mainView.loadAjaxContentModal('/admin/collection/create', 'createCollectionsModal', 'Create Collection');
  });

  // Location Map
  if(  $("input[name='locLat']").length &&  $("input[name='locLon']").length ) {
    var latInput = $("input[name='locLat']");
    var lonInput = $("input[name='locLon']");
    var locationContainer = latInput.parent().parent();

    latInput.parent().hide();
    lonInput.parent().hide();

    locationContainer.append('<div id="resourceLocationMap" style="width:100%;height:300px;"></div>');

    var latValue = 0;
    var lonValue = 0;
    var zoom = 2;

    if( latInput.val() != '' && latInput.val() != '') {
      latValue = parseFloat(latInput.val());
      lonValue = parseFloat(lonInput.val());
      zoom = 10;
    }

    // gmaps init
    var mapOptions = {
      center: { lat: latValue, lng: lonValue },
      zoom: zoom
    };
    var map = new google.maps.Map(document.getElementById('resourceLocationMap'), mapOptions);

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
