$( document ).ready( function() {

  if( $('.poiModal').length > 0 ) {
    alert('inizializa modal pois, instanciamos aquí mapa');
    //initializeMap(formId);
    rextPoiJs.bindPoiForm('.poiModal ');
  }
  else{
    rextPoiJs.bindPoiForm('');
  }

  rextPoiJs.moveSubmitBtn('#createPoiModal');
  rextPoiJs.moveSubmitBtn('#editModalForm');
} );


var rextPoiJs = {
  bindPoiForm: function( modal ) {
    var that = this;
    // tuneamos los selectores
    $(modal+'select.cgmMForm-field-rextPoi_rextPoiType').multiList( {
      orientation: 'horizontal',
      icon: '<i class="fas fa-arrows-alt"></i>',
      placeholder: __('Select options')
    } );
  },

  moveSubmitBtn: function( query ) {
    var that = this;
    var buttonsToMove = $(query).find('.gzzAdminToMove');

    if( buttonsToMove.length > 0 ) {
      buttonsToMove.each( function() {
        var that = this;
        var cloneButtonBottom = $(this).clone(true, true);
        cloneButtonBottom.appendTo( $(query+" .modal-footer") );
        $(this).hide();
      } );
    }
  },

  successResourceForm: function( data ) {
    var that = this;
    if( $('#collPois option[value='+data.id+']').length > 0 ) {
      $('#collPois option[value='+data.id+']').text(data.title);
      $('#editModalForm').modal('hide');
    }
    else {
      $('#collPois').append('<option data-image="'+data.image+'" selected="selected" value="'+data.id+'">'+data.title+'</option>');
      $('#createPoiModal').modal('hide');
    }
    $('#collPois').trigger('change');
  }
};
