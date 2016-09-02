
$('document').ready(function(){
  adminRextRoutesFileUpload( $('form.cgmMForm-form-resourceEdit').attr('id') );
  $('select.cgmMForm-field-rExtRoutes_difficultyEnvironment').select2();
  $('select.cgmMForm-field-rExtRoutes_difficultyItinerary').select2();
  $('select.cgmMForm-field-rExtRoutes_difficultyDisplacement').select2();
  $('select.cgmMForm-field-rExtRoutes_difficultyEffort').select2();
  $('select.cgmMForm-field-rExtRoutes_difficultyGlobal').select2();
});



function adminRextRoutesFileUpload( idForm, fieldName ) {

  var routesCollection = new geozzy.rextRoutes.routeCollection();


  routesCollection.url = '/api/adminRoutes/idForm/' + $('input[name=cgIntFrmId][form='+idForm+']').val() ;
  routesCollection.model= geozzy.rextRoutes.routeModel;
  routesCollection.fetch({
    success: function( ) {
      var ruta = routesCollection.get( 1 );


      // If resource is not created, set centroid from route
      if( typeof resourceViewData.id == 'undefined' ) {
        resourceMap.setCenter(new google.maps.LatLng(ruta.get('centroid')[0],ruta.get('centroid')[1])); //(ruta.get('centroid'));
        resourceMap.setZoom(10);
        resourceMarker.setPosition(new google.maps.LatLng(ruta.get('centroid')[0],ruta.get('centroid')[1]));
      }

      var route = new geozzy.rextRoutes.routeView({
        map: resourceMap,
        routeModel: ruta
      });
    }
  });
}
