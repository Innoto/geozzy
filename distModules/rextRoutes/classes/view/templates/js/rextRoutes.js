
$( document ).ready(function() {

  var routesCollection = new geozzy.rextRoutes.routeCollection();
  routesCollection.fetch({
    success: function( res ) {
      var route = new geozzy.rextRoutes.routeView();
    }
  });

});
