/**
 *  RExtLike Controller
 */
var geozzy = geozzy || {};

geozzy.likesView = geozzy.likesView || {

  setRemoveIcon: function setRemoveIcon() {
    cogumelo.log( 'setRemoveIcon' );

    // Element to send delete order
    $('.likesElement .likesImage').append( $( '<i>' ).addClass( 'likesDelete fa fa-trash' )
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
    cogumelo.log( 'eventRemove' );
    event.stopPropagation();

    $wrap = $( event.target ).closest( '.likesElement' );
    var resource = $wrap.data('id');

    // var $wrap = $fileField.closest( '.cgmMForm-wrap.cgmMForm-field-' + fieldName );

    geozzy.rExtLikeController.setStatus( resource, 0 );
    $wrap.remove();

  }
} // geozzy.likesView



$( document ).ready(function() {

  cogumelo.log( 'CARGA JS LIKES' );

  geozzy.likesView.setRemoveIcon();
});
