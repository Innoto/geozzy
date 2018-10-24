/**
 *  RExtLike Controller
 */
var geozzy = geozzy || {};

geozzy.likesView = geozzy.likesView || {

  setRemoveIcon: function setRemoveIcon() {
    console.log( 'setRemoveIcon' );

    // Element to send delete order
    $('.likesElement .likesImage').append( $( '<i>' ).addClass( 'likesDelete fas fa-trash-alt' )
      .attr( {
        'data-toggle': 'tooltip',
        'data-placement': 'left',
        'title': __('Remove from likes'),
        'tabindex': '0'
      } )
      .on( 'click', geozzy.likesView.eventRemove )
    );
  }, // setRemoveIcon
  eventRemove: function eventRemove( event ) {
    console.log( 'eventRemove' );
    event.stopPropagation();

    $wrap = $( event.target ).closest( '.likesElement' );
    var resource = $wrap.data('id');

    // var $wrap = $fileField.closest( '.cgmMForm-wrap.cgmMForm-field-' + fieldName );

    geozzy.rExtLikeController.setStatus( resource, 0 );
    $wrap.remove();

  }
} // geozzy.likesView



$( document ).ready(function() {

  console.log( 'CARGA JS LIKES' );

  geozzy.likesView.setRemoveIcon();
});
