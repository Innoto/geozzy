
$( document ).ready( function() {
  if( $('form.cgmMForm-form-resourceEdit').length > 0 ) {
    adminRextRoutesJs.fileUpload( $('form.cgmMForm-form-resourceEdit').attr('id') );
  }
  $('select.cgmMForm-field-rExtRoutes_difficultyEnvironment').select2();
  $('select.cgmMForm-field-rExtRoutes_difficultyItinerary').select2();
  $('select.cgmMForm-field-rExtRoutes_difficultyDisplacement').select2();
  $('select.cgmMForm-field-rExtRoutes_difficultyEffort').select2();
  $('select.cgmMForm-field-rExtRoutes_difficultyGlobal').select2();
} );


var adminRextRoutesJs = {
  fileUpload: function( idForm, fieldName ) {
    var that = this;
    var routesCollection = new geozzy.rextRoutes.routeCollection();

    routesCollection.url = '/api/adminRoutes/idForm/' + $('input[name=cgIntFrmId][form='+idForm+']').val();
    routesCollection.model= geozzy.rextRoutes.routeModel;
    routesCollection.fetch( {
      success: function( ) {
        var ruta = routesCollection.get( 1 );

        var resourceMap = resourceFormMaps[0].resourceMap;
        var resourceMarker = resourceFormMaps[0].resourceMarker;

        // If resource is not created, set centroid from route
        if( typeof resourceViewData.id == 'undefined' ) {
          resourceMap.setCenter(new google.maps.LatLng(ruta.get('centroid')[0],ruta.get('centroid')[1])); //(ruta.get('centroid'));
          resourceMap.setZoom(10);
          resourceMarker.setPosition(new google.maps.LatLng(ruta.get('centroid')[0],ruta.get('centroid')[1]));
        }

        $('input[form='+idForm+'].cgmMForm-field-locLat').val( ruta.get('centroid')[0] );
        $('input[form='+idForm+'].cgmMForm-field-locLon').val( ruta.get('centroid')[1] );

        // start and end from route
        $('input[form='+idForm+'].cgmMForm-field-rExtRoutes_locStartLat').val(ruta.get('start')[0]);
        $('input[form='+idForm+'].cgmMForm-field-rExtRoutes_locStartLon').val(ruta.get('start')[1]);

        $('input[form='+idForm+'].cgmMForm-field-rExtRoutes_locEndLat').val(ruta.get('end')[0]);
        $('input[form='+idForm+'].cgmMForm-field-rExtRoutes_locEndLon').val(ruta.get('end')[1]);

        var route = new geozzy.rextRoutes.routeView( {
          map: resourceMap,
          routeModel: ruta
        } );
      }
    } );
  }
};
