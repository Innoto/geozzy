var geozzy = geozzy || {};
if(!geozzy.explorerDisplay) geozzy.explorerDisplay={};

geozzy.explorerDisplay.map = Backbone.View.extend({

  parentExplorer: false ,
  map: false ,
  clusterize:false ,

  setMap: function( mapObj ) {
    this.map = mapObj;
  },

  getVisibleResourceIds: function() {
    var visibleResources = this.parentExplorer.resourceCurrentIndex.setPerPage(600);
    return visibleResources.pluck( 'id' );
  },




  render: function() {
    var that = this;

    var markers = [];


    var my_marker = {
      url: media+'/module/admin/img/geozzy_marker.png',
      // This marker is 20 pixels wide by 36 pixels high.
      size: new google.maps.Size(30, 36),
      // The origin for this image is (0, 0).
      origin: new google.maps.Point(0, 0),
      // The anchor for this image is the base of the flagpole at (0, 36).
      anchor: new google.maps.Point(13, 36)
    };


    $.each( that.parentExplorer.resourceCurrentIndex.toJSON(), function(i,e) {
/*
      new google.maps.Marker({
        position: new google.maps.LatLng( e.lat, e.lng ),
        map: resourceMap,
        title: toString(e.id)

      });
*/



      var marker = new google.maps.Marker({
        position: new google.maps.LatLng( e.lat, e.lng ),
        icon: my_marker
      });
      markers.push(marker);


    });

    var markerClusterer = new MarkerClusterer(resourceMap, markers, {
      maxZoom: 15,
      gridSize: 45,
      zoomOnClick: false
    });

    that.parentExplorer.timeDebugerMain.log( '&nbsp;- Pintado Mapa '+that.parentExplorer.resourceCurrentIndex.length+ 'recursos' );
  }

});
