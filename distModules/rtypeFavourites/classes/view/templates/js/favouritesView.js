/**
 *  RExtFavourite Controller
 */
var geozzy = geozzy || {};

geozzy.favouritesView = geozzy.favouritesView || {

  setRemoveIcon: function setRemoveIcon() {
    console.log( 'setRemoveIcon' );

    // Element to send delete order
    $('.favouritesElement').append( $( '<i>' ).addClass( 'favDelete fa fa-trash' )
      // .attr( { 'data-fieldname': fieldName, 'data-form_id': idForm } )
      .on( 'click', geozzy.favouritesView.eventRemove )
    );
    /*
      var $fileField = $( 'input[name="' + fieldName + '"][form="'+idForm+'"]' );
      var $fileFieldWrap = $fileField.closest( '.cgmMForm-wrap.cgmMForm-field-' + fieldName );

      $fileField.attr( 'readonly', 'readonly' );
      $fileField.prop( 'disabled', true );
      $fileField.hide();

      //$( '#'+fieldName+'-error[data-form_id="'+idForm+'"]' ).hide();
      $( '#' + $fileField.attr('id') + '-error' ).remove();

      // Show Title file field
      $( '.cgmMForm-' + idForm+'.cgmMForm-titleFileField_'+fieldName ).show();
      // console.log( 'SHOW: .cgmMForm-' + idForm+'.cgmMForm-titleFileField_'+fieldName );

      var $fileFieldInfo = $( '<div>' ).addClass( 'fileFieldInfo fileUploadOK formFileDelete' )
        .attr( { 'data-fieldname': fieldName, 'data-form_id': idForm } );

      // Element to send delete order
      $fileFieldInfo.append( $( '<i>' ).addClass( 'formFileDelete fa fa-trash' )
        .attr( { 'data-fieldname': fieldName, 'data-form_id': idForm } )
        .on( 'click', deleteFormFileEvent )
      );

      if( fileModId === false || !fileType || fileType.indexOf( 'image' ) !== 0 ) {
        $fileFieldInfo.append( '<div class="tnImage" style="text-align: center; line-height: 3em;">' +
          '<i class="fa fa-file fa-5x" style="color: rgb(90, 183, 128);"></i><br>' +
          fileName + '</div>' );
      }
      else {
        $fileFieldInfo.append( '<img class="tnImage" src="/cgmlImg/' + fileModId + '/fast/' +
          fileModId + '.jpg" alt="' + fileName + ' - Uploaded OK" title="' + fileName + ' - Uploaded OK"></img>' );
      }

      $fileFieldWrap.append( $fileFieldInfo );

      if( fileModId === false ) {
        loadImageTh( idForm, fieldName, fileName, $fileFieldWrap );
      }

      removeFileFieldDropZone( idForm, fieldName );
    */
  }, // setRemoveIcon
  eventRemove: function eventRemove( event ) {
    console.log( 'eventRemove' );
    event.stopPropagation();

    $wrap = $( event.target ).closest( '.favouritesElement' );
    var resource = $wrap.data('id');

    // var $wrap = $fileField.closest( '.cgmMForm-wrap.cgmMForm-field-' + fieldName );

    geozzy.rExtFavouriteController.setStatus( resource, 0 );
    $wrap.remove();

  }
} // geozzy.favouritesView



$( document ).ready(function() {

  console.log( 'CARGA JS FAVOURITES' );

  geozzy.favouritesView.setRemoveIcon();
});

