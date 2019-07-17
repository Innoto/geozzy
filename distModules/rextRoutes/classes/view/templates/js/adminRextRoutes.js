var geozzy = geozzy || {};

$(document).ready(function(){
  geozzy.adminRextRoutesViewInstance =
  geozzy.adminRextMapPolygonViewInstance = new geozzy.adminRextRoutesView();
  resourceFormMap.addComponent( geozzy.adminRextMapPolygonViewInstance );
});


geozzy.adminRextRoutesViewInstance = false;
geozzy.adminRextRoutesView = Backbone.View.extend({
  render: function() {
    var that = this;

    that.parent.$el.find( '.resourceLocationFrame .locationDialog' ).append('<div class="routesFormMap" style="display:none;"></div>');
    that.parent.$el.find( '.resourceLocationFrame .locationDialog .routesFormMap' ).html( $('.rExtRoutes').html() );
    that.parent.$el.find('.rExtRoutes').remove();

    if( $('form.cgmMForm-form-resourceEdit').length > 0 ) {
      that.fileUpload( $('form.cgmMForm-form-resourceEdit').attr('id') );
    }

    that.parent.addToolBarbutton({
        id: 'position',
        icon: '<i class="fa fa-road" aria-hidden="true"></i>', //<i class="fas fa-draw-polygon"></i>
        onclick: function() {
          that.startEdit();
        }
    });

    $('select.cgmMForm-field-rExtRoutes_difficultyEnvironment').select2();
    $('select.cgmMForm-field-rExtRoutes_difficultyItinerary').select2();
    $('select.cgmMForm-field-rExtRoutes_difficultyDisplacement').select2();
    $('select.cgmMForm-field-rExtRoutes_difficultyEffort').select2();
    $('select.cgmMForm-field-rExtRoutes_difficultyGlobal').select2();
  },
  fileUpload: function( idForm, fieldName ) {
    var that = this;
    var routesCollection = new geozzy.rextRoutes.routeCollection();

    routesCollection.url = '/api/adminRoutes/idForm/' + $('input[name=cgIntFrmId][form='+idForm+']').val();
    routesCollection.model= geozzy.rextRoutes.routeModel;
    routesCollection.fetch( {
      success: function( ) {
        var ruta = routesCollection.get( 1 );

        var resourceMap = that.parent.mapObject;
        var resourceMarker = geozzy.rExtMapWidgetFormPositionViewInstance.resourceMarker;

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
});


/*
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
};*/
