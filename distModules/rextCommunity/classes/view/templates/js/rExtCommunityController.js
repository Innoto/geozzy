/**
 *  RExtCommunity Controller
 */
var geozzy = geozzy || {};

/*
geozzy.rExtCommunityController = geozzy.rExtCommunityController || {

  getUrlApi: function getUrlApi() {
    var url = '/api/community';

    if( typeof cogumelo.publicConf.C_LANG === 'string' ) {
      url = '/'+cogumelo.publicConf.C_LANG+url;
    }

    return url;
  },

  setStatus: function setStatus( resource, status ) {
    var that = this;

    that.resource = resource;
    that.status = status;

    // BI register
    if( status === 1 || status === '1' || status === true ) {
      geozzy.biMetricsInstances.resource.eventCommunityd( resource, 'community' );
    }

    geozzy.userSessionInstance.userControlAccess( function() {
      geozzy.rExtCommunityController.sendSetStatus( that.resource, that.status );
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
      geozzy.rExtCommunityController.sendGetStatus( that.resource );
    });
  },

  getStatusAll: function getStatusAll( limitTo ) {
    var that = this;

    if( geozzy.userSessionInstance.user && geozzy.userSessionInstance.user.get('id') ) {
      var resources='';

      if( typeof limitTo === 'undefined' ) {
        var res=[];
        $('.rExtCommunity[data-community-resource]').each(function( index ) {
          res.push( $( this ).attr( 'data-community-resource' ) );
        });
        resources = res.toString();
      }
      else {
        resources = ( typeof limitTo === 'string' ) ? limitTo : limitTo.toString();
      }

      that.resource = resources;
      if( that.resource !== '' ) {
        // console.log('Facendo un sendGetStatus de varios recursos: '+that.resource );
        geozzy.rExtCommunityController.sendGetStatus( that.resource );
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
    $commField = $('.rExtCommunity[data-community-resource="'+resource+'"]');
    if( status === 1 || status === '1' || status === true ) {
      $commField.addClass( 'selected' ).attr( 'data-community-status', 1 );
    }
    else {
      $commField.removeClass( 'selected' ).attr( 'data-community-status', 0 );
    }
  },
  getStatusClient: function getStatusClient( resource ) {
    $commField = $('.rExtCommunity[data-community-resource="'+resource+'"]');
    status = $commField.attr( 'data-community-status' );
    return( status );
  },
  gotoCommunityPage: function gotoCommunityPage() {
    var that = this;

    var formData = new FormData();
    formData.append( 'cmd', 'getCommunityUrl' );
    $.ajax({
      url: this.getUrlApi(), type: 'POST',
      data: formData, cache: false, contentType: false, processData: false,
      success: function getCommunityUrlSuccess( $jsonData, $textStatus, $jqXHR ) {
        if ( $jsonData.result === 'ok' ) {
          // console.log( $jsonData.status );
          window.location = window.location.protocol+'//'+window.location.host+$jsonData.status;
        }
      }
    });
  },
  eventClick: function eventClick( event ) {
    event.stopPropagation();
    geozzy.rExtCommunityController.changeStatus( $( this ).data( 'communityResource' ) );
  },
  setBinds: function setBinds() {
    $('.rExtCommunity[data-community-bind!="1"]').attr( 'data-community-bind', 1 ).on(
      'click', geozzy.rExtCommunityController.eventClick );

    $('.rExtCommunityUserLink').css( 'cursor', 'pointer' ).on(
      'click', function() {
        geozzy.userSessionInstance.userControlAccess( function() {
          geozzy.rExtCommunityController.gotoCommunityPage();
        });
      }
    );

  },
  setBindsAndGetStatus: function setBindsAndGetStatus() {
    var that = this;

    var resources=[];
    $('.rExtCommunity[data-community-bind!="1"]').each(function( index ) {
      resources.push( $( this ).attr( 'data-community-resource' ) );
      $( this ).attr( 'data-community-bind', 1 ).on(
        'click', geozzy.rExtCommunityController.eventClick );
    });

    if( resources.length > 0 ) {
      that.getStatusAll( resources.toString() );
    }
  }
};



$(document).ready(function(){
  geozzy.rExtCommunityController.setBinds();
});


*/