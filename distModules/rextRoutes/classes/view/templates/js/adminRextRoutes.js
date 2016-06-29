

function adminRextRoutesFileUpload( idForm, fieldName ) {

  // Desactivar como chegar en caso de existir
  /*if(typeof geozzy.rExtMapDirectionsController.mapClickEvent != 'undefined' ){
    console.log( geozzy.rExtMapDirectionsController.mapClickEvent.remove());
  }*/

  var routesCollection = new geozzy.rextRoutes.routeCollection();


  routesCollection.url = '/api/adminRoutes/idForm/' + $('input[name=cgIntFrmId][form='+idForm+']').val() ;
  routesCollection.fetch({
    success: function( res ) {/*
      var route = new geozzy.rextRoutes.routeView({
        map: geozzy.rExtMapInstance.resourceMap,
        routeModel: routesCollection.get( geozzy.rExtRoutesOptions.resourceId )
      });
    */}
  });
}
