var geozzy = geozzy || {};

/*
  HTML example to add a form map

*/

geozzy.rExtMapWidgetForm = function( divMapa ) {
  var that = this;

  that.initializeMap = function( segmentDIV ){
    // Location Map
    if(  segmentDIV.find(".lat input").length &&  segmentDIV.find(".lon input").length  ) {
      that.latInput = segmentDIV.find(".lat input");
      that.lonInput = segmentDIV.find(".lon input");
      that.defaultZoom = segmentDIV.find(".zoom input");
      that.mapContainer = segmentDIV.find(".mapContainer");

      if( that.mapContainer.length && that.mapContainer.children('.resourceLocationMap').length < 1 ) {
        that.mapContainer.append('<div class="resourceLocationMap"></div>');

        var latValue = 0;
        var lonValue = 0;
        var zoom = 3;

        if(typeof cogumelo.publicConf.admin.adminMap === 'undefined') {
          console.log('cogumelo.publicConf.admin.adminMap is not defined in conf')
        }
        else {
          var latInit = Number(cogumelo.publicConf.admin.adminMap.defaultLat);
          var lonInit = Number(cogumelo.publicConf.admin.adminMap.defaultLon);
          var zoomInit = Number(cogumelo.publicConf.admin.adminMap.defaultZoom);
          var defaultMarker = cogumelo.publicConf.admin.adminMap.marker;
        }

        if( that.latInput.val() !== '' && that.latInput.val() !== '') {
          latValue = parseFloat( that.latInput.val() );
          lonValue = parseFloat( that.lonInput.val() );
          latInit = latValue;
          lonInit = lonValue;
        }

        if( that.defaultZoom.length > 0 &&  that.defaultZoom.val() != '') {
          zoom = parseInt( that.defaultZoom.val() );
        }

        zoomInit = zoom;

        // gmaps init
        var mapOptions = {
          center: { lat: latInit, lng: lonInit },
          zoom: zoomInit,
          scrollwheel: false
        };
        var resourceMap = new google.maps.Map( segmentDIV.find('.resourceLocationMap')[0], mapOptions);

        // add marker

        var my_marker = {
          url: defaultMarker,
          // This marker is 20 pixels wide by 36 pixels high.
          size: new google.maps.Size(30, 36),
          // The origin for this image is (0, 0).
          origin: new google.maps.Point(0, 0),
          // The anchor for this image is the base of the flagpole at (0, 36).
          anchor: new google.maps.Point(13, 36)
        };

        var resourceMarker = new google.maps.Marker({
          position: new google.maps.LatLng( latValue, lonValue ),
          title: 'Resource location',
          icon: my_marker,
          draggable: true
        });

        // Draggend event
        google.maps.event.addListener( resourceMarker,'dragend',function(e) {
          that.latInput.val( resourceMarker.position.lat() );
          that.lonInput.val( resourceMarker.position.lng() );
        });

        // Click map event
        google.maps.event.addListener(resourceMap, 'click', function(e) {
          resourceMarker.setPosition( e.latLng );
          resourceMarker.setMap( resourceMap );
          that.latInput.val( resourceMarker.position.lat() );
          that.lonInput.val( resourceMarker.position.lng() );

          that.defaultZoom.val( resourceMap.getZoom() );
        });

        // map zoom changed
        google.maps.event.addListener(resourceMap, 'zoom_changed', function(e) {
          that.defaultZoom.val( resourceMap.getZoom() );
        });

        if( that.latInput.val() !== '') {
          resourceMarker.setMap( resourceMap);
        }
      }
    }
  };

  that.initializeMap(divMapa);

}
