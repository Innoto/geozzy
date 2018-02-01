var geozzy = geozzy || {};

geozzy.travelPlannerLoader = geozzy.travelPlannerLoader || {
  getUrlApi: function getUrlApi() {
    var url = '/api/travelplanner';

    if( typeof cogumelo.publicConf.C_LANG === 'string' ) {
      url = '/'+cogumelo.publicConf.C_LANG+url;
    }

    return url;
  },
  gotoTravelPlannerPage: function gotoTravelPlannerPage() {
    var that = this;

    var formData = new FormData();
    formData.append( 'cmd', 'getTravelPlannerUrl' );
    $.ajax({
      url: this.getUrlApi(), type: 'POST',
      data: formData, cache: false, contentType: false, processData: false,
      success: function getTravelPlannerUrlSuccess( $jsonData, $textStatus, $jqXHR ) {
        if ( $jsonData.result === 'ok' ) {
          window.location = window.location.protocol+'//'+window.location.host+$jsonData.status;
        }
      }
    });
  },
  setBinds: function setBinds() {
    geozzy.travelPlannerLoader.destroyBinds();
    $('.rExtTravelPlannerLink').css( 'cursor', 'pointer' ).on(
      'click', function() {
        geozzy.userSessionInstance.userControlAccess( function() {
          geozzy.travelPlannerLoader.gotoTravelPlannerPage();
        });
      }
    );
  },
  destroyBinds: function destroyBinds(){
    $('.rExtTravelPlannerLink').off('click');
  }
};

$(document).ready(function(){
  geozzy.travelPlannerLoader.setBinds();
});
