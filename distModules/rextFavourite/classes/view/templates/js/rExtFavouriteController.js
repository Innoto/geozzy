/**
 *  RExtFavourite Controller
 */
var geozzy = geozzy || {};

geozzy.rExtFavouriteController = geozzy.rExtFavouriteController || {
  setStatus: function setStatus( resource, status ) {
    var that = this;
    that.resource = resource;
    that.status = status;
    geozzy.userSessionInstance.userControlAccess( function() {
      geozzy.rExtFavouriteController.sendSetStatus( that );
    });
  },
  sendSetStatus: function sendSetStatus( that ) {
    var formData = new FormData();
    formData.append( 'cmd', 'setStatus' );
    formData.append( 'resourceId', that.resource );
    formData.append( 'status', that.status );
    $.ajax({
      url: '/api/favourites', type: 'POST',
      data: formData, cache: false, contentType: false, processData: false,
      success: function setStatusSuccess( $jsonData, $textStatus, $jqXHR ) {
        if ( $jsonData.result === 'ok' ) {
          that.setStatusClient( that.resource, $jsonData.status );
        }
      }
    });
  },
  getStatus: function getStatus( resource ) {
    var that = this;
    that.resource = resource;
    geozzy.userSessionInstance.userControlAccess( function() {
      geozzy.rExtFavouriteController.sendGetStatus( that );
    });
  },
  sendGetStatus: function sendGetStatus( that ) {
    var formData = new FormData();
    formData.append( 'cmd', 'getStatus' );
    formData.append( 'resourceId', that.resource );
    $.ajax({
      url: '/api/favourites', type: 'POST',
      data: formData, cache: false, contentType: false, processData: false,
      success: function setStatusSuccess( $jsonData, $textStatus, $jqXHR ) {
        if ( $jsonData.result === 'ok' ) {
          that.setStatusClient( that.resource, $jsonData.status );
        }
      }
    });
  },
  changeStatus: function changeStatus( resource ) {
    status = this.getStatusClient( resource );
    newStatus = ( status === 1 || status === '1' || status === true ) ? 0 : 1;
    this.setStatus( resource, newStatus );
  },
  setStatusClient: function setStatusClient( resource, status ) {
    $favField = $('.rExtFavourite[data-favourite-resource="'+resource+'"]');
    if( status === 1 || status === '1' || status === true ) {
      $favField.addClass( 'selected' ).attr( 'data-favourite-status', 1 );
    }
    else {
      $favField.removeClass( 'selected' ).attr( 'data-favourite-status', 0 );
    }
  },
  getStatusClient: function getStatusClient( resource ) {
    $favField = $('.rExtFavourite[data-favourite-resource="'+resource+'"]');
    status = $favField.attr( 'data-favourite-status' );
    return( status );
  },
  eventClick: function eventClick() {
    geozzy.rExtFavouriteController.changeStatus( $( this ).data( 'favouriteResource' ) );
  },
  setBinds: function setBinds() {
    $('.rExtFavourite[data-favourite-bind!="1"]').attr( 'data-favourite-bind', 1 ).on(
      'click', geozzy.rExtFavouriteController.eventClick );
  }
};



$(document).ready(function(){
  geozzy.rExtFavouriteController.setBinds();
});
