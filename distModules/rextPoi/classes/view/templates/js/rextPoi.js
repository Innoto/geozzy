$(document).ready(function(){

  if($('.poiModal').length >0){
    //initializeMap(formId);
    bindPoiForm('.poiModal ');
  }
  else{
    bindPoiForm('');
  }

  moveSubmitBtn('#createPoiModal');
  moveSubmitBtn('#editModalForm');
});


function bindPoiForm(modal){

  // tuneamos los selectores
  $(modal+'select.cgmMForm-field-rextPoi_rextPoiType').multiList({
    orientation: 'horizontal',
    icon: '<i class="fas fa-arrows-alt"></i>',
    placeholder: __('Select options')
  });

}


function moveSubmitBtn(query){

  var buttonsToMove = $(query).find('.gzzAdminToMove');

  if( buttonsToMove.length > 0 ){
    buttonsToMove.each( function() {
      var that = this;
      var cloneButtonBottom = $(this).clone(true, true);
      cloneButtonBottom.appendTo( $(query+" .modal-footer") );
      $(this).hide();
    });
  }
}


function successResourceForm( data ){

  if( $('#collPois option[value='+data.id+']').length > 0 ){
    $('#collPois option[value='+data.id+']').text(data.title);
    $('#editModalForm').modal('hide');
  }else{
    $('#collPois').append('<option data-image="'+data.image+'" selected="selected" value="'+data.id+'">'+data.title+'</option>');
    $('#createPoiModal').modal('hide');
  }
  $('#collPois').trigger('change');
}
