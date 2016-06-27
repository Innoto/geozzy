
$( document ).ready(function() {


  if( typeof geozzy.rExtRoutesOptions != 'undefined' && typeof geozzy.rExtMapInstance != 'undefined') {


    // Desactivar como chegar en caso de existir
    if(typeof geozzy.rExtMapDirectionsController.mapClickEvent != 'undefined' ){
      console.log( geozzy.rExtMapDirectionsController.mapClickEvent.remove());
    }

    var routesCollection = new geozzy.rextRoutes.routeCollection();

    routesCollection.url = '/api/routes/id/' + geozzy.rExtRoutesOptions.resourceId
    routesCollection.fetch({
      success: function( res ) {
        var route = new geozzy.rextRoutes.routeView({
          map: geozzy.rExtMapInstance.resourceMap,
          routeModel: routesCollection.get( geozzy.rExtRoutesOptions.resourceId )
        });
      }
    });
  }
  else {
    console.log('Routes: resource id or MAP^not found');
  }

});
