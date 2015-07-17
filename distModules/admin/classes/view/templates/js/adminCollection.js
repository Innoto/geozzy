
$(window).ready(function(){



  $('#collResources').multiList({
    orientation: 'Horizontal',
    itemImage: true,
    icon: '<i class="fa fa-arrows"></i>'
  });


  var buttonsToMove = $('.modal .gzzAdminToMove');
  console.log(buttonsToMove);

  if( buttonsToMove.size() > 0 ){
    buttonsToMove.each( function() {
      var that = this;
      var cloneButtonBottom = $(this).clone();
      cloneButtonBottom.appendTo( ".modal-footer" );
      $(this).hide();
    });
  }
});
