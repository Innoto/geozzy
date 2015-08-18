
$(document).ready(function(){
  rtypeBinds();
  moveHTML();
});

function rtypeBinds(){

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
