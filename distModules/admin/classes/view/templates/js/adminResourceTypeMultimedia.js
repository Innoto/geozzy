
$(document).ready(function(){

  moveSubmitBtn('#createResourceExternalModal');
  moveSubmitBtn('#createResourceLocalModal');
  linkOrEmbed();
});


function moveSubmitBtn(query){
  var buttonsToMove = $(query).find('.gzzAdminToMove');

  if( buttonsToMove.length > 0 ){
    buttonsToMove.each( function() {
      var that = this;
      var cloneButtonBottom = $(this).clone(true, true);
      cloneButtonBottom.appendTo( $(query+" .modal-footer") );
      $(this).hide();
    });
  }
}

function linkOrEmbed (){
  var linkOrEmbedValue = $('#linkOrEmbed').val();
  $('.linkOrEmbedContainer').hide();
  $('.linkOrEmbed_'+linkOrEmbedValue).show();

  $('#linkOrEmbed').change(function(){
    $('.linkOrEmbedContainer').hide();
    $('.linkOrEmbed_'+this.value).show();
  });
}
