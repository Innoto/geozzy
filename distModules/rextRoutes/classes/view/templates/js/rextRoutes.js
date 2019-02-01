
$( document ).ready(function() {

  geozzy.rExtMapInstance.onLoad(function(){
    if( typeof geozzy.rExtRoutesOptions != 'undefined' && typeof geozzy.rExtMapInstance != 'undefined' ) {
      google.maps.event.addListenerOnce(geozzy.rExtMapInstance.resourceMap, 'idle', function() {
        rextRoutesJs.setRouteOnResourceMapInstance();
      } );
    }
    else {
      cogumelo.log('Routes: resource id or MAP not found');
    }
  });
});

var rextRoutesJs = {
  setRouteOnResourceMapInstance: function() {
    var that = this;
    // Set new map container height
    $( $(geozzy.rExtMapInstance.options.wrapper)[0] ).height( cogumelo.publicConf.rextRoutesConf.newMapHeight );

    // Desactivar como chegar en caso de existir
    if(
      typeof geozzy.rExtMapDirectionsController != 'undefined' &&
      typeof geozzy.rExtMapDirectionsController.mapClickEvent != 'undefined' &&
      typeof geozzy.rExtMapDirectionsController.mapClickEvent.remove != 'undefined'
    ){
      geozzy.rExtMapDirectionsController.mapClickEvent.remove();
    }

    var routesCollection = new geozzy.rextRoutes.routeCollection();

    geozzy.rExtMapInstance.resourceMarker.setMap(null);

    routesCollection.url = '/api/routes/id/' + geozzy.rExtRoutesOptions.resourceId;

    cogumelo.log( geozzy.rExtMapInstance.resourceMap );
    routesCollection.fetch( {
      success: function( res ) {
        var route = new geozzy.rextRoutes.routeView( {
          map: geozzy.rExtMapInstance.resourceMap,
          routeModel: routesCollection.get( geozzy.rExtRoutesOptions.resourceId ),
          showGraph: geozzy.rExtRoutesOptions.showGraph,
          graphContainer: geozzy.rExtRoutesOptions.graphContainer
        } );
      }
    } );
  }
};
