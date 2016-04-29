$(document).ready(function(){

  if($('.poiModal').size()>0){
    //bindModalEventForm();
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
    icon: '<i class="fa fa-arrows"></i>'
  });

}


function moveSubmitBtn(query){



  var buttonsToMove = $(query).find('.gzzAdminToMove');

  if( buttonsToMove.size() > 0 ){
    buttonsToMove.each( function() {
      var that = this;
      var cloneButtonBottom = $(this).clone();
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
