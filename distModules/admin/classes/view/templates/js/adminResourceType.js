
$(document).ready(function(){
  
  moveSubmitBtn('.modal .cgmMForm-form-resourceFileCreate .gzzAdminToMove');
  moveSubmitBtn('.modal .cgmMForm-form-resourceUrlCreate .gzzAdminToMove');
});


function moveSubmitBtn(query){
  var buttonsToMove = $(query);

  if( buttonsToMove.size() > 0 ){
    buttonsToMove.each( function() {
      var that = this;
      var cloneButtonBottom = $(this).clone();
      cloneButtonBottom.appendTo( ".modal-footer" );
      $(this).hide();
    });
  }
}
