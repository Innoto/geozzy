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

    $.each( that.parentExplorer.resourceCurrentIndex.toJSON(), function(i,e) {

      new google.maps.Marker({
        position: new google.maps.LatLng( e.lat, e.lng ),
        map: resourceMap,
        title: toString(e.id)

      });
    });
    that.parentExplorer.timeDebugerMain.log( '&nbsp;- Pintado Mapa '+that.parentExplorer.resourceCurrentIndex.length+ 'recursos' );
  }

});
