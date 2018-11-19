/**
 *  RExtCommunity Controller
 */
var geozzy = geozzy || {};

geozzy.rExtCommunityController = geozzy.rExtCommunityController || {

  getUrlApi: function getUrlApi() {
    var url = '/api/community';

    if( typeof cogumelo.publicConf.C_LANG === 'string' ) {
      url = '/'+cogumelo.publicConf.C_LANG+url;
    }

    return url;
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
          // cogumelo.log( $jsonData.status );
          window.location = window.location.protocol+'//'+window.location.host+$jsonData.status;
        }
      }
    });
  },

  setBinds: function setBinds() {
    $('.rExtCommunityUserLink').css( 'cursor', 'pointer' ).on(
      'click', function() {
        geozzy.userSessionInstance.userControlAccess( function() {
          geozzy.rExtCommunityController.gotoCommunityPage();
        });
      }
    );
  }
};



$(document).ready( function(){
  geozzy.rExtCommunityController.setBinds();
});
