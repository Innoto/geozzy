
$(document).ready(function(){
  colBinds();
  moveHTML();
});

function colBinds(){
  $('#collResources').multiList({
    orientation: 'Horizontal',
    itemImage: true,
    icon: '<i class="fa fa-arrows"></i>',
    placeholder: 'Add existing resources'
  });


  $('#addResourceLocal').on('click', function(){
    //PARAMS( URL - ID - TITLE MD OR LG)
    app.mainView.loadAjaxContentModal('/admin/resourcetypefile/create/', 'createResourceLocalModal', 'Upload multimedia resource', 'md');
  });
  $('#addResourceExternal').on('click', function(){
    //PARAMS( URL - ID - TITLE - MD OR LG)
    app.mainView.loadAjaxContentModal('/admin/resourcetypeurl/create/', 'createResourceExternalModal', 'Link or embed multimedia resource', 'md');
  });
}

function moveHTML(){

  var buttonsToMove = $('.modal .gzzAdminToMove');

  if( buttonsToMove.size() > 0 ){
    buttonsToMove.each( function() {
      var that = this;
      var cloneButtonBottom = $(this).clone();
      cloneButtonBottom.appendTo( ".modal-footer" );
      $(this).hide();
    });
  }
}


function successResourceForm( data ){
  //resource

  $('#collResources').append('<option data-image="/cgmlformfilews/'+data.image+'" selected="selected" value="'+data.id+'">'+data.title+'</option>');
  $('#createResourceLocalModal, #createResourceExternalModal').modal('hide');

  $('#collResources').trigger('change');
  //End resource
}
