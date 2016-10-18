/**
 *  RExtFavourite Controller
 */
var geozzy = geozzy || {};

geozzy.communityView = geozzy.communityView || {

  setRemoveIcon: function setRemoveIcon() {
    console.log( 'setRemoveIcon' );

    // Element to send delete order
    $('.communityElement .favsImage').append( $( '<i>' ).addClass( 'favsDelete fa fa-trash' )
      .on( 'click', geozzy.communityView.eventRemove )
    );
  }, // setRemoveIcon
  eventRemove: function eventRemove( event ) {
    console.log( 'eventRemove' );
    event.stopPropagation();

    $wrap = $( event.target ).closest( '.communityElement' );
    var resource = $wrap.data('id');

    // var $wrap = $fileField.closest( '.cgmMForm-wrap.cgmMForm-field-' + fieldName );

    geozzy.rExtFavouriteController.setStatus( resource, 0 );
    $wrap.remove();

  }
} // geozzy.communityView



$( document ).ready(function() {

  console.log( 'CARGA JS FAVOURITES' );

  geozzy.communityView.setRemoveIcon();
});

