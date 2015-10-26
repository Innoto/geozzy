var geozzy = geozzy || {};
if(!geozzy.explorerDisplay) geozzy.explorerDisplay={};

geozzy.explorerDisplay.mapView = Backbone.View.extend({

  displayType: 'map',
  parentExplorer: false ,
  map: false ,
  projection: false,
  ready:false,
  clusterize:false ,

  markers: false,
  markerClusterer: false,

  bufferPixels:150,

  setMap: function( mapObj ) {
    this.map = mapObj;
    this.setMapEvents();
  },

  setMapEvents: function() {
    var that = this;

    // drag event on map
    google.maps.event.addListener(this.map, "dragend", function() {
      that.ready = true;
      that.parentExplorer.render(true);
    });

    // zoom event on map
    google.maps.event.addListener(this.map, "zoom_changed", function() {
      that.ready = true;
      that.parentExplorer.render(true);
    });

  },

  getVisibleResourceIds: function() {

    var that = this;
    // AQUÍ hai que seleccionar os que están dentro dos bounds, non o paxinado

    var visibleResources = [];

    that.parentExplorer.resourceMinimalList.each(function(m, index) {
      // Assign values 2:visible in map, 1:not visible in map but present in buffer zone, 0:not in map or buffer
      m.set( 'mapVisible', that.coordsInMap( m.get('lat'), m.get('lng') ) );
    });

    //console.log(visibleResources.length)

    return visibleResources;
  },




  render: function() {

    var that = this;

    that.my_marker_icon = {
      url: media+'/module/admin/img/geozzy_marker.png',
      // This marker is 20 pixels wide by 36 pixels high.
      size: new google.maps.Size(30, 36),
      // The origin for this image is (0, 0).
      origin: new google.maps.Point(0, 0),
      // The anchor for this image is the base of the flagpole at (0, 36).
      anchor: new google.maps.Point(13, 36)
    };


    that.renderWithCluster();/*
    if( that.clusterize != false ) {
      that.renderWithCluster();

    }
    else {
      that.renderWithoutCluster();

    }*/



    that.parentExplorer.timeDebugerMain.log( '&nbsp;- Pintado Mapa '+that.parentExplorer.resourceIndex.length+ 'recursos' );
  },


  renderWithoutCluster: function() {
    var that = this;

    if( that.markers.length > 0 ){
      $.each(  that.markers , function(i,e) {
        e.setMap( null );
      });

    }

    that.markers = [];

    $.each( that.parentExplorer.resourceIndex.toJSON(), function(i,e) {
      var marker = new google.maps.Marker({
        position: new google.maps.LatLng( e.lat, e.lng ),
        icon: that.my_marker_icon,
        map: that.map
      });

      marker.addListener('click', function() {
        that.markerEvent('click');
      });
      marker.addListener('mouseover', function() {
        that.markerEvent('hover');
      });

      that.markers.push(marker);
    });
  },

  renderWithCluster: function() {
    var that = this;


    that.markers = [];

    $.each( that.parentExplorer.resourceIndex.toJSON(), function(i,e) {
      var marker = new google.maps.Marker({
        position: new google.maps.LatLng( e.lat, e.lng ),
        icon: that.my_marker_icon,
      });
      marker.addListener('click', function() {
        that.markerEvent('click');
      });
      marker.addListener('mouseover', function() {
        that.markerEvent('hover');
      });


      that.markers.push(marker);
    });



    if( that.markerClusterer == false ) {

      that.markerClusterer = new MarkerClusterer(this.map, that.markers, {
        maxZoom: 15,
        gridSize: 45,
        zoomOnClick: false
      });

    }
    else {
      this.markerClusterer.clearMarkers();
      this.markerClusterer.addMarkers( that.markers );
      this.markerClusterer.redraw();
    }
  },

  coordsInMap: function( lat, lng ) {
    var that = this;


    var ret = 0; // NOT IN MAP OR BUFFER

    var b =  that.map.getBounds();

    var sw = b.getSouthWest();
    var ne = b.getNorthEast();

    var scale = Math.pow(2, that.map.getZoom());

    var swBuffer = new google.maps.Point(   that.map.getProjection().fromLatLngToPoint(sw ).x- that.bufferPixels /scale,   that.map.getProjection().fromLatLngToPoint(sw).y+ that.bufferPixels /scale );
    var neBuffer = new google.maps.Point(   that.map.getProjection().fromLatLngToPoint(ne ).x+ that.bufferPixels /scale ,   that.map.getProjection().fromLatLngToPoint(ne).y- that.bufferPixels /scale );

    var swB = that.map.getProjection().fromPointToLatLng( swBuffer );
    var neB = that.map.getProjection().fromPointToLatLng( neBuffer );


    if( lat < ne.lat() && lng < ne.lng() && lat > sw.lat() && lng > sw.lng() ) {
      ret = 2; // IN MAP AREA
    }
    else if(lat < neB.lat() && lng < neB.lng() && lat > swB.lat() && lng > swB.lng() ) {
      ret = 1; // NOT IN MAP AREA BUT IN BUFFER
    }

    return ret;
  },

  isReady: function() {
    return this.ready;
  },



  markerEvent: function( eventType, data ) {
    console.log('Evento tipo :'+eventType);
  }


});
