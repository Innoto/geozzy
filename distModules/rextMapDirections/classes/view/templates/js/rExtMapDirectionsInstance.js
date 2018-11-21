
$(document).ready( function() {

  //geozzy.rExtMapInstance.onReadyEvent( function() {alert(2)});
  //cogumelo.log(geozzy.rExtMapInstance.resourceMap)
  if( geozzy.rExtMapInstance.resourceMap ) {
    if( cogumelo.publicConf.mod_detectMobile_isMobile && !cogumelo.publicConf.mod_detectMobile_isTablet ) {
      $( '.rextMapDirectionsButton' ).on( 'click', function(event) {
        if( typeof geozzy.rExtMapDirectionsData === 'object' ) {
          geozzy.rExtMapDirectionsController.redirectGMaps( geozzy.rExtMapDirectionsData );
        }
        // return false;
        event.preventDefault();
      } );
    }
    else{
      if( typeof geozzy.rExtMapDirectionsData === 'object' ) {
        geozzy.rExtMapDirectionsController.prepareRoutes( geozzy.rExtMapDirectionsData );
      }
    }
  }
});
