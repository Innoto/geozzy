
$(document).ready(function(){
  moveSubmitBtn('#createResLocalMultipleModal');
});


function moveSubmitBtn(query){
  var buttonsToMove = $(query).find('.gzzAdminToMove');

  if( buttonsToMove.size() > 0 ){
    buttonsToMove.each( function() {
      var that = this;
      var cloneButtonBottom = $(this).clone(true, true);
      cloneButtonBottom.appendTo( $(query+" .modal-footer") );
      $(this).hide();
    });
  }
}
