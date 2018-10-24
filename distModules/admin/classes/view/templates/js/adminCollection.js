
$(document).ready(function(){
  colBinds();
  moveHTML();
});

function colBinds(){
  $('#collResources').multiList({
    orientation: 'Horizontal',
    itemImage: true,
    icon: '<i class="fas fa-arrows-alt fa-fw"></i>',
    placeholder: __('Add existing resources')
  });


  $('#addResourceLocal').on('click', function(){
    //PARAMS( URL - ID - DATA MD OR LG)
    app.mainView.loadAjaxContentModal('/admin/resourcetypefile/create/', 'createResourceLocalModal', { title: __('Upload multimedia resource') }, 'md');
  });
  $('#addResLocalMultiple').on('click', function(){
    app.mainView.loadAjaxContentModal('/admin/resmultifile/create/', 'createResLocalMultipleModal', { title: __('Upload multiple files') }, 'md');
  });
  $('#addResourceExternal').on('click', function(){
    app.mainView.loadAjaxContentModal('/admin/resourcetypeurl/create/', 'createResourceExternalModal', { title: __('Link or embed multimedia resource') }, 'md');
  });
}

function moveHTML(){

  var buttonsToMove = $('.modal .gzzAdminToMove');

  if( buttonsToMove.length > 0 ){
    buttonsToMove.each( function() {
      var that = this;
      var cloneButtonBottom = $(this).clone(true, true);
      cloneButtonBottom.appendTo( ".modal-footer" );
      $(this).hide();
    });
  }
}


function successResourceForm( data ){
  //resource
  $('#collResources').append('<option data-image="'+data.image+'" selected="selected" value="'+data.id+'">'+data.title+'</option>');
  $('#createResourceLocalModal, #createResourceExternalModal').modal('hide');
  $('#collResources').trigger('change');
  //End resource
}

//Success para MultiFile
function successResourceArrayForm( dataArray ){
  //resource
  $.each(dataArray, function (key, data) {
    $('#collResources').append('<option data-image="'+data.image+'" selected="selected" value="'+data.id+'">'+data.title+'</option>');
  });
  $('#createResLocalMultipleModal').modal('hide');
  $('#collResources').trigger('change');
  //End resource
}
