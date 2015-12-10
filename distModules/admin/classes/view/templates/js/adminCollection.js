
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
  var urlImg;

  urlImg = '/cgmlImg/'+data.image+'/square_cut/'+data.image;
  /*Kw11
  if(data.image){

  }
  else{

  }*/

  $('#collResources').append('<option data-image="'+urlImg+'" selected="selected" value="'+data.id+'">'+data.title+'</option>');
  $('#createResourceLocalModal, #createResourceExternalModal').modal('hide');

  $('#collResources').trigger('change');
  //End resource
}
/*Kw11
function ytVidId(url) {
  var p = /^(?:https?:\/\/)?(?:www\.)?(?:youtu\.be\/|youtube\.com\/(?:embed\/|v\/|watch\?v=|watch\?.+&v=))((\w|-){11})(?:\S+)?$/;
  return (url.match(p)) ? RegExp.$1 : false;
}
*/
// for example snippet only!
document.body.addEventListener('click', function(e) {
    if (e.target.className == 'yt-url' && 'undefined' !== e.target.value) {
        var ytId = ytVidId(e.target.value);
        alert(e.target.value + "\r\nResult: " + (!ytId ? 'false' : ytId));
    }
}, false);
