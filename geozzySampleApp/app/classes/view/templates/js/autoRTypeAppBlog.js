

$( document ).ready( function() {
  //  DESPLEGAR CONTENIDO OCULTO
  var altura = parseInt( $( '.infoPersonal' ).css( 'height' ) );
  var alturaContent = parseInt( $( '.contenidoInfoTotal' ).css( 'height' ) );
  if( alturaContent >= altura ) {
    $( '.verMas[data-info-becario=infoBecario]' ).show();
  }
  $( '.verMas' ).on( 'click', function() {
    $( '.verMas[data-info-becario=infoBecario]' ).hide();
    $( '.infoPersonal' ).css( 'height', 'auto');
  } );

  geozzy.commentInstance.setUserCallback( function (id){
    
    $.ajax({
      url: '/api/becariourl/idbecario/' + id,
      type: 'GET',
      success: function setStatusSuccess( $jsonData, $textStatus, $jqXHR ) {
        if( $textStatus === 'success' && $jsonData.hasOwnProperty('url') ) {
          redirectToPerfilBecario( $jsonData.url );
        }
      }
    });
  });

});

function redirectToPerfilBecario( url ) {
  $( location ).attr( 'href', url );
}
