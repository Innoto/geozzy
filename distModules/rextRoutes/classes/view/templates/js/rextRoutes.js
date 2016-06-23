
$( document ).ready(function() {


  if( typeof geozzy.rExtRoutesOptions != 'undefined') {
    var routesCollection = new geozzy.rextRoutes.routeCollection();

    routesCollection.url = '/api/routes/id/' + geozzy.rExtRoutesOptions.resourceId
    routesCollection.fetch({
      success: function( res ) {
        var route = new geozzy.rextRoutes.routeView();
      }
    });
  }
  else {
    console.log('Routes: resource id not found');
  }

});
