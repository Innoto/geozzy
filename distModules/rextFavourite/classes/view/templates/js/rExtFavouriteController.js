/**
 *  RExtFavourite Controller
 */
var geozzy = geozzy || {};

geozzy.rExtFavouriteController = geozzy.rExtFavouriteController || {
  config: {

  },
  info: {

  },
  activate: function activate( resource ) {
    console.log( 'rExtFavouriteController.activate');
  },
  deactivate: function deactivate( resource ) {
    console.log( 'rExtFavouriteController.deactivate');
  },
  setStatus: function setStatus( resource, status ) {
    console.log( 'rExtFavouriteController.setStatus: '+resource+', '+status );
    var that = this;

    that.resource = resource;
    that.status = status;

    var formData = new FormData();
    formData.append( 'execute', 'setStatus' );
    formData.append( 'resource', that.resource );
    formData.append( 'status', that.status );

    $.ajax({
      url: '/geozzyFavourite/command', type: 'POST',
      data: formData, cache: false, contentType: false, processData: false,
      success: function setStatusSuccess( $jsonData, $textStatus, $jqXHR ) {
        console.log( 'rExtFavouriteController.setStatus $jsonData --- ', $jsonData );

        if ( $jsonData.result === 'ok' ) {
          that.setStatusClient( that.resource, $jsonData.status );
        }
      }
    });
  },
  getStatus: function getStatus( resource ) {
    console.log( 'rExtFavouriteController.getStatus: '+resource);
    var that = this;

    that.resource = resource;
    that.status = status;

    var formData = new FormData();
    formData.append( 'execute', 'getStatus' );
    formData.append( 'resource', that.resource );

    $.ajax({
      url: '/geozzyFavourite/command', type: 'POST',
      data: formData, cache: false, contentType: false, processData: false,
      success: function setStatusSuccess( $jsonData, $textStatus, $jqXHR ) {
        console.log( 'rExtFavouriteController.getStatus $jsonData --- ', $jsonData );

        if ( $jsonData.result === 'ok' ) {
          that.setStatusClient( that.resource, $jsonData.status );
        }
      }
    });
  },
  changeStatus: function changeStatus( resource ) {
    console.log( 'rExtFavouriteController.change');
    status = this.getStatusClient( resource );
    newStatus = ( status === 1 || status === '1' || status === true ) ? 0 : 1;
    this.setStatus( resource, newStatus );
  },
  setStatusClient: function setStatusClient( resource, status ) {
    console.log( 'rExtFavouriteController.setStatusClient: '+resource+', '+status );
    $favField = $('.rExtFavourite[data-favourite-resource="'+resource+'"]');
    console.log($favField);
    if( status === 1 || status === '1' || status === true ) {
      $favField.addClass( 'selected' ).attr( 'data-favourite-status', 1 );
    }
    else {
      $favField.removeClass( 'selected' ).attr( 'data-favourite-status', 0 );
    }
  },
  getStatusClient: function getStatusClient( resource ) {
    console.log( 'rExtFavouriteController.getStatusClient: '+resource );
    $favField = $('.rExtFavourite[data-favourite-resource="'+resource+'"]');
    status = $favField.attr( 'data-favourite-status' );
    console.log( 'rExtFavouriteController.getStatusClient: status='+status );
    return( status );
  },
  eventClick: function eventClick() {
    console.log( 'rExtFavouriteController.eventClick', $( this ).data() );
    geozzy.rExtFavouriteController.changeStatus( $( this ).data( 'favouriteResource' ) );
  },
  setBinds: function setBinds() {
    console.log( 'rExtFavouriteController.setBinds' );
    $('.rExtFavourite[data-favourite-bind!="1"]').attr( 'data-favourite-bind', 1 ).on( 'click', geozzy.rExtFavouriteController.eventClick );
  }
};



$(document).ready(function(){
  console.log( 'rExtFavouriteController.ready');
  geozzy.rExtFavouriteController.setBinds();
});
