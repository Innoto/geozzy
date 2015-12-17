var geozzy = geozzy || {};
if(!geozzy.explorerDisplay) geozzy.explorerDisplay={};

geozzy.explorerDisplay.mapView = Backbone.View.extend({

  displayType: 'map',
  parentExplorer: false ,
  map: false ,
  projection: false,
  ready:false,

  markersCreated: false,
  markerClusterer: false,

  lastCenter: false,

  mapZones: {
    outerMargin: {x:300,y:300},
    innerMargin:{x:400,y:90},
  },

  initialize: function( opts ) {

    this.options = new Object({
      map : false,
      clusterize: false,
      chooseMarkerIcon: function() {return false}
    });
    $.extend(true, this.options, opts);

    this.setMap( this.options.map );
  },

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
/*
    // map first load
    google.maps.event.addListener(this.map, "idle", function() {
      that.ready = true;
      that.parentExplorer.render(true);
    });
*/
  },

  getVisibleResourceIds: function() {

    var that = this;
    // AQUÍ hai que seleccionar os que están dentro dos bounds, non o paxinado

    var visibleResources = [];

    that.parentExplorer.resourceMinimalList.each(function(m, index) {
      // Assign values 2:visible in map, 1:not visible in map but present in buffer zone, 0:not in map or buffer
      that.parentExplorer.resourceMinimalList.get(m.get('id')).set( 'mapVisible', that.coordsInMap( m.get('lat'), m.get('lng') ) );
      //m.set( 'mapVisible', that.coordsInMap( m.get('lat'), m.get('lng') ) );
    });

    //console.log(visibleResources.length)

    return visibleResources;
  },




  render: function() {

    var that = this;





    if( that.options.clusterize != false ) {
      that.renderWithCluster();

    }
    else {
      that.renderWithoutCluster();

    }



    that.parentExplorer.timeDebugerMain.log( '&nbsp;- Pintado Mapa '+that.parentExplorer.resourceIndex.length+ 'recursos' );
  },

  createAllMarkers: function() {
    var that = this;

    if( !that.markersCreated ) {
      that.parentExplorer.resourceMinimalList.each( function(e) {

        var marker = e.mapMarker = new google.maps.Marker({
                  position: new google.maps.LatLng( e.get('lat'), e.get('lng') ),
                  icon: that.chooseMarker(e),
                  map: that.map
                });

        marker.setVisible(false);

        marker.addListener('click', function() {
          that.markerClick( e.get('id') );
        });
        marker.addListener('mouseover', function() {
          that.markerHover( e.get('id') );
        });
        marker.addListener('mouseout', function() {
          that.markerOut( e.get('id') );
        });

        that.parentExplorer.resourceMinimalList.get( e.get('id') ).set('mapMarker', marker );

      });
    }

    that.markersCreated = true;
  },


  hideAllMarkers: function() {
    var that = this;
    that.parentExplorer.resourceMinimalList.each( function(e) {
      e.mapMarker.setVisible(false);
    });
  },

  renderWithoutCluster: function() {
    var that = this;

    if( !that.markersCreated ) {
      that.createAllMarkers();
    }
    that.hideAllMarkers();

    that.parentExplorer.resourceIndex.each( function(e) {
      e.mapMarker.setIcon( that.chooseMarkerIcon(e) );
      e.mapMarker.setVisible(true);
    });



  },

  renderWithCluster: function() {


    var that = this;


    that.markers = [];

    if( !that.markersCreated ) {
      that.createAllMarkers();
    }
    that.hideAllMarkers();


    that.parentExplorer.resourceIndex.each( function( e ) {
      that.markers.push( e.mapMarker );
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

    google.maps.event.trigger( that.map, "resize");

    var ret = 0; // NOT IN MAP OR BUFFER

    var mb = that.getMapBounds();

    var sw = mb[0];
    var ne = mb[1];

    var scale = Math.pow(2, that.map.getZoom());

    var swOuter = new google.maps.Point(   that.coordToPixel(sw ).x- that.mapZones.outerMargin.x /scale,   that.coordToPixel(sw).y+ that.mapZones.outerMargin.y /scale );
    var neOuter = new google.maps.Point(   that.coordToPixel(ne ).x+ that.mapZones.outerMargin.x /scale ,   that.coordToPixel(ne).y- that.mapZones.outerMargin.y /scale );

    var swInner = new google.maps.Point(   that.coordToPixel(sw ).x+ that.mapZones.innerMargin.x /scale,   that.coordToPixel(sw).y- that.mapZones.innerMargin.y /scale );
    var neInner = new google.maps.Point(   that.coordToPixel(ne ).x- that.mapZones.innerMargin.x /scale ,   that.coordToPixel(ne).y+ that.mapZones.innerMargin.y /scale );



    var swO = that.map.getProjection().fromPointToLatLng( swOuter );
    var neO = that.map.getProjection().fromPointToLatLng( neOuter );
    var swI = that.map.getProjection().fromPointToLatLng( swInner );
    var neI = that.map.getProjection().fromPointToLatLng( neInner );

    if( lat < ne.lat() && lng < ne.lng() && lat > sw.lat() && lng > sw.lng() ) {

      if( lat < neI.lat() && lng < neI.lng() && lat > swI.lat() && lng > swI.lng() ) {
        ret = 3; // IN CENTER OF MAP AREA
      }
      else{
        ret = 2;
      }

    }
    else if(lat < neO.lat() && lng < neO.lng() && lat > swO.lat() && lng > swO.lng() ) {
      ret = 1; // NOT IN MAP AREA BUT IN OUTER MARGIN
    }

    return ret;
  },


  getMapBounds: function() {
    var that = this;
    return [ that.map.getBounds().getSouthWest(), that.map.getBounds().getNorthEast() ];
  },

  coordToPixel: function( latLng) {
    var that = this;
    return that.map.getProjection().fromLatLngToPoint( latLng );
  },

  isReady: function() {
    return this.ready;
  },

  chooseMarkerIcon: function( e ) {
    var that = this;

    var iconUrl = '/mediaCache/module/admin/img/geozzy_marker.png';
    var newIconUrl = that.options.chooseMarkerIcon(e);


    if( newIconUrl ) {
      console.log(newIconUrl);
      iconUrl = newIconUrl
    }

    return iconUrl;
  },

  chooseMarker: function( e ) {

    var that = this;



    return {
      url: that.chooseMarkerIcon(e),
      // This marker is 20 pixels wide by 36 pixels high.
      size: new google.maps.Size(30, 36),
      // The origin for this image is (0, 0).
      origin: new google.maps.Point(0, 0),
      // The anchor for this image is the base of the flagpole at (0, 36).
      anchor: new google.maps.Point(13, 36)
    };
  },

  markerBounce: function(id) {
    var that = this;

    that.parentExplorer.resourceMinimalList.get( id ).get('mapMarker').setAnimation(google.maps.Animation.BOUNCE);
    setTimeout(function(){ that.parentExplorer.resourceMinimalList.get( id ).get('mapMarker').setAnimation(null); }, 800);
  },

  panTo: function( id ) {
    var that = this;

    //console.log(that.parentExplorer.resourceMinimalList.get( id ).get('mapVisible'))
    if( that.parentExplorer.resourceMinimalList.get( id ).get('mapVisible') == 1 || that.parentExplorer.resourceMinimalList.get( id ).get('mapVisible') == 2  ) {
      if( that.lastCenter == false ){
        that.lastCenter = that.map.getCenter();
      }
      that.map.panTo(  that.parentExplorer.resourceMinimalList.get( id ).get('mapMarker').getPosition() );
    }
    else {
      that.panToLastCenter();
    }
  },

  panToLastCenter: function() {
    var that = this;
    if( that.lastCenter ) {
      that.map.panTo( that.lastCenter );
      that.lastCenter = false;
    }

  },

  markerClick: function( id ){
    var that = this;
    // call metrics event
    that.parentExplorer.metricsResourceController.eventHoverStart( id, 'Explorer: '+that.parentExplorer.options.explorerSectionName );

  },

  markerHover: function( id ){
    var that = this;

    // call metrics event
    that.parentExplorer.metricsResourceController.eventHoverStart( id, 'Explorer: '+that.parentExplorer.options.explorerSectionName );

    if( that.parentExplorer.displays.mapInfo ) {
      that.parentExplorer.displays.mapInfo.show( id );
    }
  },
  markerOut: function( id ) {
    var that = this;

    // call metrics event end
    that.parentExplorer.metricsResourceController.eventHoverEnd( id );

    if( that.parentExplorer.displays.mapInfo ) {
      that.parentExplorer.displays.mapInfo.hide();
      that.panToLastCenter();
    }
  },
  clusterClick: function(){},
  clusterHover: function(){}

});
