
$(document).ready( function() {

  //geozzy.rExtMapInstance.onReadyEvent( function() {alert(2)});
  //console.log(geozzy.rExtMapInstance.resourceMap)
  if( typeof directionsData != 'undefined' ) {
    geozzy.rExtMapDirectionsController.prepareRoutes( geozzy.rExtMapDirectionsData );
  }
});
