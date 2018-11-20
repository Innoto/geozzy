var geozzy = geozzy || {};

$( document ).ready( function() {
  userProfileJs.instanceProfileMap();
  $('select.gzzSelect2').select2();
} );


var userProfileJs = {
  instanceProfileMap: function() {
    var that = this;
    // Location Map
    if( $("input[name='locLat']").length && $("input[name='locLon']").length ) {
      var latInput = $("input[name='locLat']");
      var lonInput = $("input[name='locLon']");
      var defaultZoom = $("input[name='defaultZoom']");
      //var locationContainer = latInput.parent().parent();
      var mapContainer = $('.mapContainer');

      if( mapContainer.length ) {
        mapContainer.append('<div id="resourceLocationMap" style="height:350px;"></div>');

        latInput.parent().hide();
        lonInput.parent().hide();
        defaultZoom.parent().hide();

        var latValue = 0;
        var lonValue = 0;
        var zoom = 1;

        if( latInput.val() != '' && latInput.val() != '' ) {
          latValue = parseFloat( latInput.val() );
          lonValue = parseFloat( lonInput.val() );
          zoom = parseInt( defaultZoom.val() );
        }

        // gmaps init
        var mapOptions = {
          center: { lat: latValue, lng: lonValue },
          zoom: zoom,
          scrollwheel: false
        };
        var resourceMap = new google.maps.Map( document.getElementById('resourceLocationMap'), mapOptions );

        // add marker

        var my_marker = {
          url: cogumelo.publicConf.media+'/module/admin/img/geozzy_marker.png',
          // This marker is 20 pixels wide by 36 pixels high.
          size: new google.maps.Size(30, 36),
          // The origin for this image is (0, 0).
          origin: new google.maps.Point(0, 0),
          // The anchor for this image is the base of the flagpole at (0, 36).
          anchor: new google.maps.Point(13, 36)
        };

        var resourceMarker = new google.maps.Marker( {
          position: new google.maps.LatLng( latValue, lonValue ),
          title: 'User location',
          icon: my_marker,
          draggable: true
        } );

        // Draggend event
        google.maps.event.addListener( resourceMarker,'dragend', function( e ) {
          latInput.val( resourceMarker.position.lat() );
          lonInput.val( resourceMarker.position.lng() );
        } );

        // Click map event
        google.maps.event.addListener( resourceMap, 'click', function( e ) {
          resourceMarker.setPosition( e.latLng );
          resourceMarker.setMap( resourceMap );
          latInput.val( resourceMarker.position.lat() );
          lonInput.val( resourceMarker.position.lng() );

          defaultZoom.val( resourceMap.getZoom() );
        } );

        // map zoom changed
        google.maps.event.addListener( resourceMap, 'zoom_changed', function( e ) {
          defaultZoom.val( resourceMap.getZoom() );
        } );


        if( latInput.val() != '' ) {
          resourceMarker.setMap( resourceMap );
        }

        $('.locationData .cgmMForm-field').each( function( i,e ) {
          $( e ).change( function() {
            newPos = new google.maps.LatLng( latInput.val(), lonInput.val() );
            resourceMarker.position.lat( latInput.val() );
            resourceMarker.position.lng( lonInput.val() );
            resourceMap.setZoom( parseInt( defaultZoom.val() ) );
            resourceMap.setCenter( newPos );
            resourceMarker.setPosition( newPos );
            resourceMarker.setMap( resourceMap );
          } );
        } );

      } // if( mapContainer.length )
    }
  }
};
