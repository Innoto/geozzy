/**
 *  RExtLike Controller
 */
var geozzy = geozzy || {};

/*

.rExtLike : Wrapper
  data-like-resource : Resource Id
  &.selected & data-like-status=1 : Like active
  data-like-bind=1 : Bind active
*/

geozzy.rExtLikeController = geozzy.rExtLikeController || {
  getUrlApi: function getUrlApi() {
    var url = '/api/like';

    if( typeof(cogumelo.publicConf.C_LANG) === 'string' ) {
      url = '/'+cogumelo.publicConf.C_LANG+url;
    }

    return url;
  },
  setStatus: function setStatus( resource, status ) {
    var that = this;

    status = ( status === 1 || status === '1' || status === true ) ? 1 : 0;

    that.resource = resource;
    that.status = status;

    geozzy.userSessionInstance.userControlAccess( function() {
      geozzy.rExtLikeController.sendSetStatus( that.resource, that.status );
    });
  },
  sendSetStatus: function sendSetStatus( resource, status ) {
    var that = this;

    status = ( status === 1 || status === '1' || status === true ) ? 1 : 0;

    that.resource = resource;
    that.status = status;

    var formData = new FormData();
    formData.append( 'cmd', 'setStatus' );
    formData.append( 'resourceId', that.resource );
    formData.append( 'status', that.status );
    $.ajax({
      url: this.getUrlApi(), type: 'POST',
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
      geozzy.rExtLikeController.sendGetStatus( that.resource );
    });
  },
  getStatusAll: function getStatusAll( limitTo ) {
    var that = this;

    if( geozzy.userSessionInstance.user && geozzy.userSessionInstance.user.get('id') ) {
      var resources='';

      if( typeof(limitTo) === 'undefined' ) {
        var res=[];
        $('.rExtLike[data-like-resource]').each(function( index ) {
          res.push( $( this ).attr('data-like-resource') );
        });
        resources = res.toString();
      }
      else {
        resources = ( typeof(limitTo) === 'string' ) ? limitTo : limitTo.toString();
      }

      that.resource = resources;
      if( that.resource !== '' ) {
        // cogumelo.log('Facendo un sendGetStatus de varios recursos: '+that.resource );
        geozzy.rExtLikeController.sendGetStatus( that.resource );
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
      url: this.getUrlApi(), type: 'POST',
      data: formData, cache: false, contentType: false, processData: false,
      success: function setStatusSuccess( $jsonData, $textStatus, $jqXHR ) {
        if ( $jsonData.result === 'ok' ) {
          if( typeof $jsonData.status === 'number' ) {
            that.setStatusClient( that.resource, $jsonData.status );
          }
          else {
            // cogumelo.log( 'JSON status non NUMERO. Type: '+ typeof $jsonData.status );
            // cogumelo.log( $jsonData.status );
            $.each( $jsonData.status, function( resId, resStatus ) {
              // cogumelo.log( 'resId: ' + resId + ' resStatus: ', resStatus );
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
    // cogumelo.log( 'setStatusClient: ' + typeof status.value );
    var that = this;

    var likeValue = null;
    var likeCount = 0;

    if( 'object' === typeof status ) {
      likeValue = ( status.value === 1 || status.value === '1' || status.value === true ) ? 1 : 0;
      likeCount = status.count;
    }
    else {
      likeValue = ( status === 1 || status === '1' || status === true ) ? 1 : 0;
      likeCount = '';
    }


    $likeField = $('.rExtLike[data-like-resource="'+resource+'"]');
    if( likeValue === 1 ) {
      $likeField.addClass( 'selected' ).attr( 'data-like-status', 1 );
    }
    else {
      $likeField.removeClass( 'selected' ).attr( 'data-like-status', 0 );
    }

    that.setCounterClient( resource, likeCount );
  },
  setCounterClient: function setCounterClient( resource, likeCount ) {
    $likeCountElem = $('.rExtLike[data-like-resource="'+resource+'"] .likeCount');
    if( $likeCountElem.length === 1 ) {
      $likeCountElem.text( likeCount );
    }
  },
  getStatusClient: function getStatusClient( resource ) {
    $likeField = $('.rExtLike[data-like-resource="'+resource+'"]');
    status = $likeField.attr( 'data-like-status' );
    status = ( status === 1 || status === '1' || status === true ) ? 1 : 0;

    return( status );
  },
  getCounterClient: function getCounterClient( resource ) {
    var likeCount = 0;
    $likeCountElem = $('.rExtLike[data-like-resource="'+resource+'"] .likeCount');
    if( $likeCountElem.length === 1 ) {
      likeCount = parseInt( $likeCountElem.text() );
    }

    return( likeCount );
  },
  gotoLikesPage: function gotoLikesPage() {
    var that = this;

    var formData = new FormData();
    formData.append( 'cmd', 'getLikesUrl' );
    $.ajax({
      url: this.getUrlApi(), type: 'POST',
      data: formData, cache: false, contentType: false, processData: false,
      success: function getLikesUrlSuccess( $jsonData, $textStatus, $jqXHR ) {
        if( $jsonData.result === 'ok' ) {
          // cogumelo.log( $jsonData.status );
          window.location = window.location.protocol+'//'+window.location.host+$jsonData.status;
        }
      }
    });
  },
  eventClick: function eventClick( event ) {
    event.stopPropagation();
    geozzy.rExtLikeController.changeStatus( $( this ).data( 'likeResource' ) );
  },
  setBinds: function setBinds() {
    $('.rExtLike[data-like-bind!="1"]').attr( 'data-like-bind', 1 ).on(
      'click', geozzy.rExtLikeController.eventClick );

    $('.rExtLikeUserLink').css( 'cursor', 'pointer' ).on(
      'click', function() {
        geozzy.userSessionInstance.userControlAccess( function() {
          geozzy.rExtLikeController.gotoLikesPage();
        });
      }
    );

  },
  setBindsAndGetStatus: function setBindsAndGetStatus() {
    var that = this;

    var resources=[];
    $('.rExtLike[data-like-bind!="1"]').each(function( index ) {
      resources.push( $( this ).attr( 'data-like-resource' ) );
      $( this ).attr( 'data-like-bind', 1 ).on(
        'click', geozzy.rExtLikeController.eventClick );
    });

    if( resources.length > 0 ) {
      that.getStatusAll( resources.toString() );
    }
  }
};



$(document).ready(function(){
  geozzy.rExtLikeController.setBinds();
});
