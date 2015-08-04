
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

  $('.cgmMForm-field-rExtAccommodation_accommodationType').multiList({
    orientation: 'horizontal'
  });
  $('.cgmMForm-field-rExtAccommodation_accommodationServices').multiList({
    orientation: 'horizontal'
  });
  $('.cgmMForm-field-rExtAccommodation_accommodationFacilities').multiList({
    orientation: 'horizontal'
  });
  $('.cgmMForm-field-rextEatAndDrink_eatanddrinkSpecialities').select2();

  $('#resourceAddCollection').on('click', function(){
    //PARAMS( URL - ID - TITLE )
    app.mainView.loadAjaxContentModal('/admin/collection/create', 'createCollectionsModal', 'Create Collection');
  });

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
