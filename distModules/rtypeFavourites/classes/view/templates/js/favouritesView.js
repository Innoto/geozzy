/**
 *  RExtFavourite Controller
 */
var geozzy = geozzy || {};

geozzy.favouritesView = geozzy.favouritesView || {

  setRemoveIcon: function setRemoveIcon() {
    cogumelo.log( 'setRemoveIcon' );

    // Element to send delete order
    $('.favouritesElement .favsImage').append( $( '<i>' ).addClass( 'favsDelete fa fa-trash' )
      .attr( {
        'data-toggle': 'tooltip',
        'data-placement': 'left',
        'title': __('Remove from favourites'),
        'tabindex': '0'
      } )
      .on( 'click', geozzy.favouritesView.eventRemove )
    );
  }, // setRemoveIcon
  eventRemove: function eventRemove( event ) {
    cogumelo.log( 'eventRemove' );
    event.stopPropagation();

    $wrap = $( event.target ).closest( '.favouritesElement' );
    var resource = $wrap.data('id');

    // var $wrap = $fileField.closest( '.cgmMForm-wrap.cgmMForm-field-' + fieldName );

    geozzy.rExtFavouriteController.setStatus( resource, 0 );
    $wrap.remove();

  }
} // geozzy.favouritesView



$( document ).ready(function() {

  cogumelo.log( 'CARGA JS FAVOURITES' );

  geozzy.favouritesView.setRemoveIcon();
});
