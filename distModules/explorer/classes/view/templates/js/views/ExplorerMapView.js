var geozzy = geozzy || {};
if(!geozzy.explorerDisplay) geozzy.explorerDisplay={};

geozzy.explorerDisplay.mapView = Backbone.View.extend({

  displayType: 'map',
  parentExplorer: false ,
  map: false ,
  clusterize:false ,

  markers: false,
  markerClusterer: false,


  setMap: function( mapObj ) {
    this.map = mapObj;
  },

  getVisibleResourceIds: function() {
    var visibleResources = this.parentExplorer.resourceCurrentIndex.setPerPage(600);
    return visibleResources.pluck( 'id' );
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


    $.each( that.parentExplorer.resourceCurrentIndex.toJSON(), function(i,e) {
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

    that.parentExplorer.timeDebugerMain.log( '&nbsp;- Pintado Mapa '+that.parentExplorer.resourceCurrentIndex.length+ 'recursos' );
  }

});
