


var geozzy = geozzy || {};
if(!geozzy.explorerComponents) geozzy.explorerComponents={};

geozzy.explorerComponents.mapView = Backbone.View.extend({

  displayType: 'map',
  parentExplorer: false ,
  map: false ,
  projection: false,
  ready:false,

  markersCreated: false,
  markerClusterer: false,

  outerPanToIntervalometer: false,
  outerPanToIntervalometerValue: false,

  mapArrowMarker: false,
//  markerClustererHover: false,

  lastCenter: false,


  initialize: function( options ) {
    var that = this;
    var opts = new Object({
      map : false,
      clusterize: false,
      clustererStyles: false,
      clustererMaxZoom: 15,
      clustererGridSize: 90,
      clustererZoomOnClick: true,
      mapArrowImage: cogumelo.publicConf.media+'/module/explorer/img/mapArrow.png',
      chooseMarkerIcon: function() {return false},
      mapZones: {
        outerMargin: {
          left:200,
          top:100,
          right:200,
          bottom:100
        },
        innerMargin:{
          left:400,
          top:60 ,
          right:60,
          bottom:60
        },
      }
    });


    that.options = $.extend(true, {}, opts, options );

    that.setMap( this.options.map );

  },

  setParentExplorer: function( parentExplorer ) {
    var  that = this;
    that.parentExplorer = parentExplorer;
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

    google.maps.event.addListener(this.map, "click", function() {
      if(that.parentExplorer.explorerTouchDevice) {
        //that.markerOut( 0 );
        that.parentExplorer.triggerEvent('resourceMouseOut',{id:0});
      }
    });

    // zoom event on map
    google.maps.event.addListener(this.map, "zoom_changed", function() {
      that.ready = true;
      that.parentExplorer.render(true);
      that.parentExplorer.triggerEvent('zoomChanged', {});
    });

    // map first load
    google.maps.event.addListener(this.map, "idle", function() {

      if( that.ready !== true) {
        that.ready = true;
        that.parentExplorer.render(true);
      }

    });

  },

  getVisibleResourceIds: function() {

    var that = this;
    // AQUÍ hai que seleccionar os que están dentro dos bounds, non o paxinado

    var visibleResources = [];

    google.maps.event.trigger( that.map, "resize");

    that.parentExplorer.resourceMinimalList.each(function(m, index) {
      // Assign values 2:visible in map, 1:not visible in map but present in buffer zone, 0:not in map or buffer

      var markerPosition = that.aboutMarkerPosition( m.get('lat'), m.get('lng') );
      /*var markerPosition = {
        outerZone: false,
        inMap: 3,
        distanceToInnerMargin: false
      };*/

      //that.parentExplorer.resourceMinimalList.get(m.get('id')).set( 'mapOuterZone', markerPosition.outerZone );
      that.parentExplorer.resourceMinimalList.get(m.get('id')).set( 'mapVisible', markerPosition.inMap  );

      that.parentExplorer.resourceMinimalList.get(m.get('id')).set( 'arrowAngle', markerPosition.arrowAngle  );
      that.parentExplorer.resourceMinimalList.get(m.get('id')).set( 'intersectsWithInnerBox', markerPosition.intersectsWithInnerBox  );
      that.parentExplorer.resourceMinimalList.get(m.get('id')).set( 'mapDistanceToInnerMargin', markerPosition.distanceToInnerMargin  );
      //m.set( 'mapVisible', that.coordsInMap( m.get('lat'), m.get('lng') ) );
    });

    //console.log(visibleResources.length)

    return visibleResources;
  },




  render: function() {

    var that = this;





    if( that.options.clusterize !== false ) {
      that.renderWithCluster();

    }
    else {
      that.renderWithoutCluster();

    }


    if( that.parentExplorer.options.debug ) {
      that.parentExplorer.timeDebugerMain.log( '&nbsp;- Pintado Mapa '+that.parentExplorer.resourceIndex.length+ 'recursos' );
    }
  },

  createAllMarkers: function() {
    var that = this;

    if( !that.markersCreated ) {
      that.parentExplorer.resourceMinimalList.each( function(e) {

//console.log(that.chooseMarker(e))
        var marker = e.mapMarker = new google.maps.Marker({
                  position: new google.maps.LatLng( e.get('lat'), e.get('lng') ),
                  map: that.map,
                  flat: true,
                  optimized: false,
                  visible:true
                });

        marker.setVisible(false);

        marker.explorerResourceId = e.get('id');

        marker.addListener('mousedown', function() {

          if(!that.parentExplorer.explorerTouchDevice){
            that.markerClick( e.get('id') );
          }
          else {
          //  that.markerClick( e.get('id') );
            that.markerHover( e.get('id') );
          }
        });
        marker.addListener('mouseover', function() {
          if(!that.parentExplorer.explorerTouchDevice){
            that.markerHover( e.get('id') );
          }

        });
        marker.addListener('mouseout', function() {
          if(!that.parentExplorer.explorerTouchDevice){
            that.markerOut( e.get('id') );
          }
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

  showAllMarkers: function() {
    var that = this;
    that.parentExplorer.resourceMinimalList.each( function(e) {
      e.mapMarker.setVisible(true);
    });
  },

  hide: function() {
    var that = this;

    that.hideAllMarkers();

    if( that.options.clusterize && that.markerClusterer != false  ) {
      that.markerClusterer.clearMarkers();
    }

  },

  renderWithoutCluster: function() {
    var that = this;

    if( !that.markersCreated ) {
      that.createAllMarkers();
    }
    that.hideAllMarkers();

    that.parentExplorer.resourceIndex.each( function(e) {
      e.mapMarker.setIcon( that.chooseMarker(e) );
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

/*

    that.parentExplorer.resourceIndex.each( function(e) {
      e.mapMarker.setIcon( that.chooseMarker(e) );
      e.mapMarker.setVisible(true);
    });

*/



    that.parentExplorer.resourceIndex.each( function( e ) {
      e.mapMarker.setIcon( that.chooseMarker(e) );
      e.mapMarker.setVisible(true);
      that.markers.push( e.mapMarker );
    });


    if( that.markerClusterer == false ) {


      that.markerClusterer = new MarkerClusterer(this.map, that.markers, {
        maxZoom: that.options.clustererMaxZoom, // 15,
        gridSize: that.options.clustererGridSize, //90,
        zoomOnClick: that.options.clustererZoomOnClick, //true,
        styles: that.options.clustererStyles
      });

      var roseta = new geozzy.explorerComponents.clusterRoseView({mapView: that});



      that.markerClusterer.setMouseover(
        function( markers ) {
          roseta.show( markers );

        }
      );

      that.markerClusterer.setMouseout(
        function() {

        }
      );


    }
    else {
      this.markerClusterer.clearMarkers();
      this.markerClusterer.addMarkers( that.markers );
      this.markerClusterer.redraw();
    }

  },


/*
  coordsInMap: function( lat, lng ) {
    var that = this;

    var rt = that.aboutMarkerPosition(lat,lng);
    return rt.inMap;
  },*/

  aboutMarkerPosition: function( lat, lng) {
    var that = this;

    //google.maps.event.trigger( that.map, "resize");

    var markerPixel = that.coordToPixel( new google.maps.LatLng(lat, lng) );
    var mapcenterPixel = that.coordToPixel( that.map.getCenter());

    var ret = {
      inMap:0, // NOT IN MAP OR BUFFER
      //outerZone:false,
      distanceToInnerMargin: 0
    }


    var mb = that.getMapBounds();

    var sw = mb[0];
    var ne = mb[1];

    var scale = Math.pow(2, that.map.getZoom());




    var swOuter = new google.maps.Point(   that.coordToPixel(sw ).x- that.options.mapZones.outerMargin.right /scale,   that.coordToPixel(sw).y+ that.options.mapZones.outerMargin.top /scale );
    var neOuter = new google.maps.Point(   that.coordToPixel(ne ).x+ that.options.mapZones.outerMargin.left /scale ,   that.coordToPixel(ne).y- that.options.mapZones.outerMargin.bottom /scale );

    var swInner = new google.maps.Point(   that.coordToPixel(sw ).x+ that.options.mapZones.innerMargin.left /scale,   that.coordToPixel(sw).y- that.options.mapZones.innerMargin.bottom /scale );
    var neInner = new google.maps.Point(   that.coordToPixel(ne ).x- that.options.mapZones.innerMargin.right /scale ,   that.coordToPixel(ne).y+ that.options.mapZones.innerMargin.top /scale );



    var swO = that.map.getProjection().fromPointToLatLng( swOuter );
    var neO = that.map.getProjection().fromPointToLatLng( neOuter );
    var swI = that.map.getProjection().fromPointToLatLng( swInner );
    var neI = that.map.getProjection().fromPointToLatLng( neInner );

    if( lat < ne.lat() && lng < ne.lng() && lat > sw.lat() && lng > sw.lng() ) {

      if( lat < neI.lat() && lng < neI.lng() && lat > swI.lat() && lng > swI.lng() ) {
        ret.inMap = 3; // ********* IN CENTER OF MAP AREA **********
      }
      else{
        ret.inMap = 2; // ********* IN INNER MARGIN *******
      }
    }
    else if(lat < neO.lat() && lng < neO.lng() && lat > swO.lat() && lng > swO.lng() ) {
      ret.inMap = 1; // ********* IN OUTER MARGIN ********
    }


    var centerToMarkerSegment = [ [ markerPixel.x, markerPixel .y ],[ mapcenterPixel.x, mapcenterPixel.y ] ];
    var TOPSegment = [[swInner.x, neInner.y], [neInner.x, neInner.y]];
    var RIGHTSegment = [[neInner.x, neInner.y], [neInner.x, swInner.y]];
    var BOTTOMSegment = [[swInner.x, swInner.y], [neInner.x, swInner.y]];
    var LEFTSegment = [[swInner.x, neInner.y], [swInner.x, swInner.y]];
    var currentIntersectionSegment = false;

    var lineUtils = new twoLinesIntersection()
    // TOP segment
    if( lineUtils.linesIntersect( centerToMarkerSegment, TOPSegment )){
      var currentIntersectionSegment = TOPSegment;
    }
    else
    // RIGHT segment
    if( lineUtils.linesIntersect( centerToMarkerSegment, RIGHTSegment )){
      var currentIntersectionSegment = RIGHTSegment;
    }
    else
    // BOTTOM segment
    if( lineUtils.linesIntersect( centerToMarkerSegment, BOTTOMSegment) ){
      var currentIntersectionSegment = BOTTOMSegment;
    }
    else
    // LEFT segment
    if( lineUtils.linesIntersect( centerToMarkerSegment, LEFTSegment )){
      var currentIntersectionSegment = LEFTSegment;
    }

    if( currentIntersectionSegment ){
      ret.intersectsWithInnerBox = lineUtils.getIntersectionPoint( centerToMarkerSegment , currentIntersectionSegment);
      ret.distanceToInnerMargin = Math.sqrt( Math.pow( markerPixel.y - ret.intersectsWithInnerBox.y, 2 )  + Math.pow( markerPixel.x - ret.intersectsWithInnerBox.x, 2) );


      if( mapcenterPixel.x > markerPixel.x) {
        var r = 180;
      }
      else {
        var r = 0;
      }

      var dy = mapcenterPixel.y - markerPixel.y;
      var dx = mapcenterPixel.x -markerPixel.x;
      ret.arrowAngle = Math.atan(dy/dx)
      ret.arrowAngle *= 180/Math.PI // rads to degs
      ret.arrowAngle = ret.arrowAngle + r + 90;
    }
    else {
      ret.intersectsWithInnerBox = 0;
      ret.distanceToInnerMargin = 0;
      ret.arrowAngle = 0;
    }

    return ret;
  },

  getMapBoundsInArray: function() {
    var that = this;
    var bounds = that.getMapBounds();

    return [ [bounds[0].lat(),bounds[0].lng()], [bounds[1].lat(),bounds[1].lng()] ];
  },

  getMapBounds: function() {
    var that = this;

    return [ that.map.getBounds().getSouthWest(), that.map.getBounds().getNorthEast() ];
  },

  coordToPixel: function( latLng) {
    var that = this;
    return that.map.getProjection().fromLatLngToPoint( latLng );
  },

  pixelToCoord: function( x, y) {
    var that = this;

    return that.map.getProjection().fromPointToLatLng( new google.maps.Point( x ,y ) );
  },


  isReady: function() {
    return this.ready;
  },


  chooseMarker: function( e ) {
    var that = this;
    var retObj;

    var iconOptions = that.options.chooseMarkerIcon(e);
    if(iconOptions) {
      retObj = iconOptions;
    }
    else {
      retObj = {
        url: cogumelo.publicConf.media+'/module/admin/img/geozzy_marker.png',
        // This marker is 20 pixels wide by 36 pixels high.
        size: new google.maps.Size(30, 36),
        // The origin for this image is (0, 0).
        origin: new google.maps.Point(0, 0),
        // The anchor for this image is the base of the flagpole at (0, 36).
        anchor: new google.maps.Point(13, 36)
      };
    }

    return retObj;



  },

  markerBounce: function(id) {
    var that = this;

    //if(!that.parentExplorer.explorerTouchDevice) {
    that.parentExplorer.resourceMinimalList.get( id ).get('mapMarker').setOptions({
      title: 'selected'
    });
    //}

    that.parentExplorer.resourceMinimalList.get( id ).get('mapMarker').setMap( null );


    that.parentExplorer.resourceMinimalList.get( id ).get('mapMarker').setMap( that.map );


    //that.parentExplorer.resourceMinimalList.get( id ).get('mapMarker').setAnimation(google.maps.Animation.BOUNCE);
    //setTimeout(function(){ that.parentExplorer.resourceMinimalList.get( id ).get('mapMarker').setAnimation(null); }, 800);
  },

  markerBounceEnd: function(id) {
    var that = this;

    that.parentExplorer.resourceMinimalList.get( id ).get('mapMarker').setOptions({
      title: ''
    });
    that.parentExplorer.resourceMinimalList.get( id ).get('mapMarker').setMap( null );

    if( that.options.clusterize != false ) {

      $(that.markerClusterer.clusters_).each( function(i,e){
        if( e.isMarkerAlreadyAdded( that.parentExplorer.resourceMinimalList.get( id ).get('mapMarker') ) == true ) {
          if(e.markers_.length == 1) {
            that.parentExplorer.resourceMinimalList.get( id ).get('mapMarker').setMap( that.map );
          }
        }
      });
    }
    else {
      that.parentExplorer.resourceMinimalList.get( id ).get('mapMarker').setMap( that.map );
    }

  },

  panTo: function( id, forcePan ) {
    var that = this;
    var mapVisible = that.parentExplorer.resourceMinimalList.get( id ).get('mapVisible');
    //var mapOuterZone = that.parentExplorer.resourceMinimalList.get( id ).get('mapOuterZone');

    var intersectsWithInnerBox = that.parentExplorer.resourceMinimalList.get( id ).get('intersectsWithInnerBox');
    var mapDistanceToInnerMargin = that.parentExplorer.resourceMinimalList.get( id ).get('mapDistanceToInnerMargin');
    var scale = Math.pow(2, that.map.getZoom());

    console.log(that.options.mapArrowImage);

    //console.log(mapVisible)
    if( mapVisible == 1 || mapVisible == 2  || forcePan == true ) {
      if( that.lastCenter == false ){
        that.lastCenter = that.map.getCenter();
      }

      //console.log(mapDistanceToInnerMargin, mapOuterZone);

      // PANTO
      var toMove = that.parentExplorer.resourceMinimalList.get( id ).get('mapMarker').getPosition() ;
      var P = that.coordToPixel( toMove )

      var fromMove = that.map.getCenter();
      var C = that.coordToPixel( fromMove );

      var pVx = P.x-C.x;
      var pVy = P.y-C.y;

      var Vx = Math.sin( Math.atan2( pVx , pVy  ) );
      var Vy = Math.cos( Math.atan2( pVx , pVy  ) );


      that.map.panTo( that.pixelToCoord( C.x + (Vx*mapDistanceToInnerMargin), C.y + (Vy*mapDistanceToInnerMargin) ) );

    }
    else {
      that.panToLastCenter();



      if( mapVisible == 0 ){
        that.outerPanTo( that.parentExplorer.resourceMinimalList.get( id ) )
      }
    }
  },


  getMapProjection: function() {
    var that = this;
    var ret = false;

    if( typeof that.options.map.getProjection() != 'undefined' ) {
      ret = that.options.map.getProjection();
    }

    return ret;
  },


  outerPanTo: function( resource ) {
    var that = this;


    var scale = Math.pow(2, that.map.zoom) * window.devicePixelRatio || 1;


    that.resetOuterPanTo();
    var intersectsWithInnerBox = resource.get('intersectsWithInnerBox');


    if( that.mapArrowMarker === false ) {
      that.mapArrowMarker = new google.maps.Marker();
    }

    console.log('ANGULO',resource.get('arrowAngle') );

    that.mapArrowMarker.setIcon( {
      url: RotateIcon.makeIcon( that.options.mapArrowImage ).setRotation({
        deg: resource.get('arrowAngle'),
        width: 32,
        height: 32
      }).getUrl(),
      anchor:new google.maps.Point(32, 32)

    });

    that.mapArrowMarker.setPosition( that.pixelToCoord( intersectsWithInnerBox.x, intersectsWithInnerBox.y ) );
    that.mapArrowMarker.setMap( that.map );


  },

  resetOuterPanTo: function() {
    that = this;

    if( that.outerPanToIntervalometer  ) {
      clearInterval( that.outerPanToIntervalometer )
      that.outerPanToIntervalometer = false;
    }

    if( that.mapArrowMarker != false ) {
      that.mapArrowMarker.setMap( null );
    }

  },

  panToLastCenter: function() {
    var that = this;

    that.resetOuterPanTo();

    if( that.lastCenter ) {
      that.map.panTo( that.lastCenter );
      that.lastCenter = false;
    }

  },

  markerClick: function( id ){

    var that = this;

    that.parentExplorer.triggerEvent('mapResourceClick', {
      id: id
    });

    that.parentExplorer.triggerEvent('resourceClick', {
      id: id,
      section: 'Explorer: '+that.parentExplorer.options.explorerSectionName
    });
  },

  markerHover: function( id ){
    var that = this;

    that.parentExplorer.triggerEvent('resourceHover', { id: id, section: 'Explorer: '+that.parentExplorer.options.explorerSectionName});

  },
  markerOut: function( id ) {
    var that = this;

    if( that.parentExplorer.displays.activeList ) {
      that.panToLastCenter();
    }

    that.parentExplorer.triggerEvent('resourceMouseOut', {id:id});

  }

});
