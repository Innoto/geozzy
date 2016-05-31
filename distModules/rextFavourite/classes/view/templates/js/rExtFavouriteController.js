/**
 *  RExtFavourite Controller
 */
var geozzy = geozzy || {};

geozzy.rExtFavouriteController = geozzy.rExtFavouriteController || {
  setStatus: function setStatus( resource, status ) {
    var that = this;

    that.resource = resource;
    that.status = status;

    // BI register
    if( status === 1 || status === '1' || status === true ) {
      geozzy.biMetricsInstances.resource.eventFavourited( resource, 'favourites' );
    }

    geozzy.userSessionInstance.userControlAccess( function() {
      geozzy.rExtFavouriteController.sendSetStatus( that.resource, that.status );
    });
  },
  sendSetStatus: function sendSetStatus( resource, status ) {
    var that = this;

    that.resource = resource;
    that.status = status;

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
      geozzy.rExtFavouriteController.sendGetStatus( that.resource );
    });
  },
  getStatusAll: function getStatusAll( limitTo ) {
    var that = this;

    if( geozzy.userSessionInstance.user && geozzy.userSessionInstance.user.get('id') ) {
      var resources='';

      if( typeof limitTo === 'undefined' ) {
        var res=[];
        $('.rExtFavourite[data-favourite-resource]').each(function( index ) {
          res.push( $( this ).attr( 'data-favourite-resource' ) );
        });
        resources = res.toString();
      }
      else {
        resources = ( typeof limitTo === 'string' ) ? limitTo : limitTo.toString();
      }

      that.resource = resources;
      if( that.resource !== '' ) {
        // console.log('Facendo un sendGetStatus de varios recursos: '+that.resource );
        geozzy.rExtFavouriteController.sendGetStatus( that.resource );
      }
    }
  },
  sendGetStatus: function sendGetStatus( resource ) {
    var that = this;

    that.resource = resource;

    var formData = new FormData();
    formData.append( 'cmd', 'getStatus' );
    formData.append( 'resourceId', that.resource );
    $.ajax({
      url: '/api/favourites', type: 'POST',
      data: formData, cache: false, contentType: false, processData: false,
      success: function setStatusSuccess( $jsonData, $textStatus, $jqXHR ) {
        if ( $jsonData.result === 'ok' ) {
          if( typeof $jsonData.status === 'number' ) {
            that.setStatusClient( that.resource, $jsonData.status );
          }
          else {
            // console.log( 'JSON status non NUMERO. Type: '+ typeof $jsonData.status );
            // console.log( $jsonData.status );
            $.each( $jsonData.status, function( resId, resStatus ) {
              // console.log( 'resId: ' + resId + ' resStatus: ', resStatus );
              that.setStatusClient( resId, resStatus );
            });
          }
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
  eventClick: function eventClick( event ) {
    event.stopPropagation();
    geozzy.rExtFavouriteController.changeStatus( $( this ).data( 'favouriteResource' ) );
  },
  setBinds: function setBinds() {
    $('.rExtFavourite[data-favourite-bind!="1"]').attr( 'data-favourite-bind', 1 ).on(
      'click', geozzy.rExtFavouriteController.eventClick );
  },
  setBindsAndGetStatus: function setBindsAndGetStatus() {
    var that = this;

    var resources=[];
    $('.rExtFavourite[data-favourite-bind!="1"]').each(function( index ) {
      resources.push( $( this ).attr( 'data-favourite-resource' ) );
      $( this ).attr( 'data-favourite-bind', 1 ).on(
        'click', geozzy.rExtFavouriteController.eventClick );
    });

    if( resources.length > 0 ) {
      that.getStatusAll( resources.toString() );
    }
  }
};



$(document).ready(function(){
  geozzy.rExtFavouriteController.setBinds();
});
