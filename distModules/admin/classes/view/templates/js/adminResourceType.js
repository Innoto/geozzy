
$(document).ready(function(){

  moveSubmitBtn('#createResourceExternalModal');
  moveSubmitBtn('#createResourceLocalModal');
});


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
