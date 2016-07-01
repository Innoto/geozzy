
$('document').ready(function(){
  adminRextRoutesFileUpload( $('form.cgmMForm-form-resourceEdit').attr('id') );
});



function adminRextRoutesFileUpload( idForm, fieldName ) {

  var routesCollection = new geozzy.rextRoutes.routeCollection();


  routesCollection.url = '/api/adminRoutes/idForm/' + $('input[name=cgIntFrmId][form='+idForm+']').val() ;
  routesCollection.model= geozzy.rextRoutes.routeModel;
  routesCollection.fetch({
    success: function( ) {
      var ruta = routesCollection.get( 1 );

      resourceMap.setCenter(new google.maps.LatLng(ruta.get('centroid')[0],ruta.get('centroid')[1])); //(ruta.get('centroid'));
      resourceMap.setZoom(10);

      resourceMarker.setPosition(new google.maps.LatLng(ruta.get('centroid')[0],ruta.get('centroid')[1]));

      var route = new geozzy.rextRoutes.routeView({
        map: resourceMap,
        routeModel: ruta
      });
    }
  });
}
