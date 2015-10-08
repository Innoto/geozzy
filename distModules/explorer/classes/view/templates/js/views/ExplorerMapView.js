var geozzy = geozzy || {};
if(!geozzy.explorerDisplay) geozzy.explorerDisplay={};

geozzy.explorerDisplay.mapView = Backbone.View.extend({

  displayType: 'map',
  parentExplorer: false ,
  map: false ,
  ready:false,
  clusterize:false ,

  markers: false,
  markerClusterer: false,


  setMap: function( mapObj ) {
    this.map = mapObj;
    this.setMapEvents();
  },

  setMapEvents: function() {
    var that = this;

    // on any map change
    google.maps.event.addListener(this.map, "idle", function() {
      that.ready = true;
      that.parentExplorer.render(true);
    });
  },

  getVisibleResourceIds: function() {

    var that = this;
    // AQUÍ hai que seleccionar os que están dentro dos bounds, non o paxinado

    var visibleResources = [];

    this.coordsInMap();
    that.parentExplorer.resourceMinimalList.each(function(m, index) {


      if( that.coordsInMap( m.get('lat'), m.get('lng') ) ) {
        m.set('mapVisible', true);
        visibleResources.push( m.get('id') )
      }
      else {
        m.set('mapVisible', false);
      }

    });

    //console.log(visibleResources.length)

    return visibleResources;
  },




  render: function() {

    var that = this;

    that.markers = [];

    var my_marker_icon = {
      url: media+'/module/admin/img/geozzy_marker.png',
      // This marker is 20 pixels wide by 36 pixels high.
      size: new google.maps.Size(30, 36),
      // The origin for this image is (0, 0).
      origin: new google.maps.Point(0, 0),
      // The anchor for this image is the base of the flagpole at (0, 36).
      anchor: new google.maps.Point(13, 36)
    };


    $.each( that.parentExplorer.resourceIndex.toJSON(), function(i,e) {
      var marker = new google.maps.Marker({
        position: new google.maps.LatLng( e.lat, e.lng ),
        icon: my_marker_icon
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

    that.parentExplorer.timeDebugerMain.log( '&nbsp;- Pintado Mapa '+that.parentExplorer.resourceIndex.length+ 'recursos' );
  },

  coordsInMap: function( lat, lng ) {
    var ret = false;
    var b =  this.map.getBounds();

    var ne = b.getNorthEast();
    var sw = b.getSouthWest();


    if( lat < ne.lat() && lng < ne.lng() &&
    lat > sw.lat() && lng > sw.lng() ) {
      ret = true;
    }

    return ret;
  },

  isReady: function() {
    return this.ready;
  }



});
