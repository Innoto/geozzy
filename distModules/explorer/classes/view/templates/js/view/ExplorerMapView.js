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

  markerClustererHover: false,

  lastCenter: false,

  mapZones: {
    outerMargin: {
      left:200,
      top:100,
      right:200,
      bottom:100
    },
    innerMargin:{
      left:400,
      top:100 ,
      right:60,
      bottom:100
    },
  },

  initialize: function( opts ) {
    var that = this;
    var options = new Object({
      map : false,
      clusterize: false,
      clustererStyles: false,
      chooseMarkerIcon: function() {return false}
    });

    that.options = $.extend(true, {}, options, opts);

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

      that.parentExplorer.resourceMinimalList.get(m.get('id')).set( 'mapOuterZone', markerPosition.outerZone );
      that.parentExplorer.resourceMinimalList.get(m.get('id')).set( 'mapVisible', markerPosition.inMap  );
      that.parentExplorer.resourceMinimalList.get(m.get('id')).set( 'mapDistanceToInnerMargin', markerPosition.distanceToInnerMargin  );
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

//console.log(that.chooseMarker(e))
        var marker = e.mapMarker = new google.maps.Marker({
                  position: new google.maps.LatLng( e.get('lat'), e.get('lng') ),
                  map: that.map,
                  flat: true,
                  optimized: false,
                  visible:true
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
        maxZoom: 15,
        gridSize: 90,
        zoomOnClick: true,
        styles: that.options.clustererStyles
      });



      that.markerClusterer.setMouseover(
        function( markers ) {
          ///
          ///
          ///
          ///
          ///
          ///
          if( markers.length > 0 ) {

            var overlay = new google.maps.OverlayView();
            overlay.draw = function() {};
            overlay.setMap(that.map);
            var proj = overlay.getProjection();
            var pos = markers[0].getPosition();
            var p = proj.fromLatLngToContainerPixel(pos);




            //$( xapita ).css('z-index','12222222');
            //$( xapita ).css({borderRadius: '10%'});

            if( !that.markerClustererHover ) {
              var insideDiv = $('<div></div>');
              that.markerClustererHover = $("<div></div>");
              that.markerClustererHover.css('position', 'absolute');
              //that.markerClustererHover.css('background', 'blue');



              insideDiv.css('background', 'grey');
              insideDiv.css('position', 'relative');
              insideDiv.css('width', '40px');
              insideDiv.css('height', '40px');

              insideDiv.css('margin', '28px');

              insideDiv.css("border-radius", insideDiv.width()/2);
              insideDiv.css("zIndex", 9);


              insideDiv.show();
              that.markerClustererHover.append( insideDiv );
              that.markerClustererHover.hide();


              $('body').append(that.markerClustererHover);



              that.markerClustererHover.css("border-radius",  that.markerClustererHover.width()/2);


              // hide event!
              $( that.markerClustererHover ).bind('mouseleave', function() {
                $( that.markerClustererHover ).hide();
              });

            }

            var top = ( $( that.map.getDiv() ).offset().top + p.y ) - that.markerClustererHover.height()/2;
            var left = ( $( that.map.getDiv() ).offset().left + p.x ) - that.markerClustererHover.width()/2;

            that.markerClustererHover.css("top", top+'px' );
            that.markerClustererHover.css("left", left+'px' );

            that.markerClustererHover.show();


            var elementsToPrint = 8;
            var angle = 7.175;
            var currentAngle = 0-angle;

            $( that.iconos ).each( function(i,e){
              e.remove();
            });

            that.iconos = [];

            $( markers ).each( function(i,e){

              currentAngle += angle;

              var icono = $('<img src="'+e.getIcon().url+'">');
              icono.css('position', 'absolute');
              //icono.css('background', 'green');
              icono.css('width', e.getIcon().size.width + 'px');
              icono.css('height', e.getIcon().size.height + 'px');
              icono.css("border-radius", 300);
              //icono.css("border", '1px solid black');
              icono.css("zIndex", 8);
              //icono.css("zIndex", 8);

              //icono.css("border-radius", icono.width()/2);
//            icono.css("top", 0  +'px' );
//            icono.css("left", 0  +'px' );

              //var iconTopOrigin = ( $( that.map.getDiv() ).offset().top + p.y ) - icono.height()/2;
              //var iconLeftOrigin = ( $( that.map.getDiv() ).offset().left + p.x ) - icono.width()/2;

              var iconTopOrigin = that.markerClustererHover.height()/2 - icono.height()/2;
              var iconLeftOrigin = that.markerClustererHover.width()/2 - icono.width()/2;

              var iconTopDest = iconTopOrigin + ( 33 * Math.sin( currentAngle ));
              var iconLeftDest = iconLeftOrigin + ( 33 * Math.cos( currentAngle ));


              icono.css("top", (iconTopOrigin)  +'px' );
              icono.css("left", (iconLeftOrigin) + 'px' );

              icono.animate({
                top: (iconTopDest)  +'px',
                left: (iconLeftDest) + 'px'
              }, 'fast' );




              that.iconos.push(icono);
            //  console.log( e.getIcon().url );
            //  console.log( e.getIcon().size.height, e.getIcon().size.width );

              icono.bind('hover', function(iconoRoseta){
                //alert('')
                console.log(iconoRoseta.target);
              });

              icono.bind('mouseleave', function(iconoRoseta){

              });



              elementsToPrint--;
              if(elementsToPrint <= 0) {
                return false;
              }


              that.markerClustererHover.append( icono );
            });



            //markers[0].setMap( that.map );
          }

          ///
          ///
          ///
          ///
          ///
          ///

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

    var ret = {
      inMap:0, // NOT IN MAP OR BUFFER
      outerZone:false,
      distanceToInnerMargin: 0
    }


    var mb = that.getMapBounds();

    var sw = mb[0];
    var ne = mb[1];

    var scale = Math.pow(2, that.map.getZoom());




    var swOuter = new google.maps.Point(   that.coordToPixel(sw ).x- that.mapZones.outerMargin.right /scale,   that.coordToPixel(sw).y+ that.mapZones.outerMargin.top /scale );
    var neOuter = new google.maps.Point(   that.coordToPixel(ne ).x+ that.mapZones.outerMargin.left /scale ,   that.coordToPixel(ne).y- that.mapZones.outerMargin.bottom /scale );

    var swInner = new google.maps.Point(   that.coordToPixel(sw ).x+ that.mapZones.innerMargin.left /scale,   that.coordToPixel(sw).y- that.mapZones.innerMargin.bottom /scale );
    var neInner = new google.maps.Point(   that.coordToPixel(ne ).x- that.mapZones.innerMargin.right /scale ,   that.coordToPixel(ne).y+ that.mapZones.innerMargin.top /scale );



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

    oNe = neI;
    oSw = swI;



    // Get outer position
    if(  lat > neI.lat() && lng < swI.lng() ) {
      ret.outerZone = 'NW';
      ret.distanceToInnerMargin = Math.sqrt( Math.pow( markerPixel.y - neInner.y, 2 )  + Math.pow( markerPixel.x - swInner.x, 2) );
    }
    else
    if( lat < swI.lat() && lng > neI.lng() ) {
      ret.outerZone = 'SE';
      ret.distanceToInnerMargin = Math.sqrt( Math.pow( markerPixel.y - swInner.y, 2 )  + Math.pow( markerPixel.x - neInner.x, 2) );
    }
    else
    if( lat > neI.lat() && lng > neI.lng() ){
      ret.outerZone = 'NE';
      ret.distanceToInnerMargin = Math.sqrt( Math.pow( markerPixel.y - neInner.y, 2 )  + Math.pow( markerPixel.x - neInner.x, 2) );
    }
    else
    if( lat < swI.lat() && lng < swI.lng() ) {
      ret.outerZone = 'SW';
      ret.distanceToInnerMargin = Math.sqrt( Math.pow( markerPixel.y - swInner.y, 2 )  + Math.pow( markerPixel.x - swInner.x, 2) );
    }
    else
    if( lat < swI.lat() ) {
      ret.outerZone = 'S';
      ret.distanceToInnerMargin = markerPixel.y - swInner.y;
    }
    else
    if( lat > neI.lat() ) {
      ret.outerZone = 'N';
      ret.distanceToInnerMargin = -(markerPixel.y -neInner.y);
    }
    else
    if( lng < swI.lng() ) {
      ret.outerZone = 'W';
      ret.distanceToInnerMargin = -(markerPixel.x -swInner.x);
    }
    else
    if( lng > neI.lng() ) {
      ret.outerZone = 'E';
      ret.distanceToInnerMargin = markerPixel.x - neInner.x;
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

  pixelToCoord: function( x, y) {
    var that = this;

    return that.map.getProjection().fromPointToLatLng( new google.maps.Point( x ,y ) );
  },


  isReady: function() {
    return this.ready;
  },


  chooseMarker: function( e ) {

    var that = this;


    var that = this;
    var retObj;

    var iconOptions = that.options.chooseMarkerIcon(e);
    if(iconOptions) {
      retObj = iconOptions;
    }
    else {
      retObj = {
        url: '/mediaCache/module/admin/img/geozzy_marker.png',
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

    that.parentExplorer.resourceMinimalList.get( id ).get('mapMarker').setOptions({
      title: 'selected'
    });
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
    that.parentExplorer.resourceMinimalList.get( id ).get('mapMarker').setMap( that.map );

  },

  panTo: function( id ) {
    var that = this;
    var mapVisible = that.parentExplorer.resourceMinimalList.get( id ).get('mapVisible');
    var mapOuterZone = that.parentExplorer.resourceMinimalList.get( id ).get('mapOuterZone');
    var mapDistanceToInnerMargin = that.parentExplorer.resourceMinimalList.get( id ).get('mapDistanceToInnerMargin');
    var scale = Math.pow(2, that.map.getZoom());

    //console.log(mapVisible)
    if( mapVisible == 1 || mapVisible == 2  ) {
      if( that.lastCenter == false ){
        that.lastCenter = that.map.getCenter();
      }

      console.log(mapDistanceToInnerMargin, mapOuterZone);
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

    //that.parentExplorer.options.resourceAccess( id )
    that.parentExplorer.explorerRouter.navigate('resource/'+id, {trigger:true});

    // call metrics event
    that.parentExplorer.metricsResourceController.eventClick( id, 'Explorer: '+that.parentExplorer.options.explorerSectionName );

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
