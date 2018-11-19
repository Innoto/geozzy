var geozzy = geozzy || {};
if(!geozzy.storyComponents) geozzy.storyComponents={};

geozzy.storyComponents.StoryBackgroundView = Backbone.View.extend({
  displayType: 'background',
  parentStory: false,
  blockSoftAnimation: false,

  scrollDirection: 1,
  scrollPosition:0,

  currentStepDOM:false,
  currentStepGeoLatLng: false,
  drawPointerLine: false,

  initialize: function( opts ) {
    var that = this;

    var options = new Object({
      map: false,
      drawLine:true,
      lineColor: '#ffffff',
      lineWidth: 2,
      lineDotRadious: 7,
      moveToStep:true,
      noPanZone:{
        left:300,
        top:200 ,
        right:10,
        bottom:200
      }
    });

    that.options = $.extend(true, {}, options, opts);
    $(window).resize(function(){ // refresca canvas
      google.maps.event.trigger(that.options.map, 'zoom_changed');
      that.updateCanvasLayer();
    });
  },

  setParentStory: function( obj ) {
    var that = this;

    that.parentStory = obj;
  },

  render: function() {
    var that = this;

    that.parentStory.bindEvent('stepChange', function(obj){
      //cogumelo.log(obj);
      that.setStep(obj);
    });

    that.setCanvasLayer();
    $(window).on('scroll', function(){
      that.softAnimation( $(this) );
      that.updateCanvasLayer();
    } );
  },

  setStep: function( obj ) {
    var that = this;

    var step = that.parentStory.storySteps.get( obj.id );
    that.currentStepDOM = obj.domElement;

    var loc = false;


    that.setMapType( step.get('mapType') );


    if( that.options.moveToStep === true && step.get('lat') && typeof step.get('lng') ) {
      that.drawPointerLine = step.get('drawLine');
      that.currentStepGeoLatLng = { lat: step.get('lat'), lng: step.get('lng') };

      if( that.latlngInNoPanZone( that.currentStepGeoLatLng.lat , that.currentStepGeoLatLng.lng ) == false || that.options.map.getZoom() != step.get('defaultZoom') ) {

        that.blockSoftAnimation = true;

        if( that.options.map.getZoom() > step.get('defaultZoom') ) {
          that.options.map.setZoom(step.get('defaultZoom'));
          window.setTimeout(function(){
            that.options.map.panTo( new google.maps.LatLng( that.currentStepGeoLatLng.lat, that.currentStepGeoLatLng.lng ) );
            that.blockSoftAnimation = false;
          }, 400);
        }
        else {
          that.options.map.panTo( new google.maps.LatLng( that.currentStepGeoLatLng.lat, that.currentStepGeoLatLng.lng ) );
          window.setTimeout(function(){
            that.options.map.setZoom(step.get('defaultZoom'));
            that.blockSoftAnimation = false;
          }, 400);
        }
      }

    }
  },

  setMapType: function( maptype ) {
    var that = this;

    switch( maptype ) {
      case 'satellite':
        var finalMaptype = google.maps.MapTypeId.SATELLITE;
        break;
      case 'roadmap':
        var finalMaptype = google.maps.MapTypeId.ROADMAP;
        break;
      case 'hybrid':
        var finalMaptype = google.maps.MapTypeId.HYBRID;
        break;
      case 'terrain':
        var finalMaptype = google.maps.MapTypeId.TERRAIN;
        break;
      default:
        var finalMaptype = google.maps.MapTypeId.SATELLITE;
        break;
    }

    that.options.map.setMapTypeId( finalMaptype );
  },

  setScrollDirection: function( scroll ) {
    var that = this;

    if( scroll.scrollTop() > that.scrollPosition ) {
      that.scrollDirection = 1;
    }
    else {
      that.scrollDirection = -1;
    }

  },

  softAnimation: function( scroll ) {

    var that = this;

    that.setScrollDirection(scroll);

    if( that.blockSoftAnimation === false ) {
      that.scrollPosition = scroll.scrollTop();
      that.options.map.panBy( 0 ,  1 * that.scrollDirection );
    }

  },



  setCanvasLayer: function() {
    var that = this;
    // initialize the canvasLayer
    var canvasLayerOptions = {
      map: that.options.map,
      resizeHandler: function(){ that.resizeCanvasLayer(); },
      animate: false,
      updateHandler: function(){ that.updateCanvasLayer(); },
      resolutionScale: function(){ return window.devicePixelRatio || 1; }
    };

    that.canvasLayer = new CanvasLayer(canvasLayerOptions);
    that.layerContext = that.canvasLayer.canvas.getContext('2d');
  },

  resizeCanvasLayer: function() {

  },

  updateCanvasLayer: function() {
    var that = this;

    // clear previous canvas contents
    var canvasWidth = that.canvasLayer.canvas.width;
    var canvasHeight = that.canvasLayer.canvas.height;
    that.layerContext.clearRect(0, 0, canvasWidth, canvasHeight);

    var mapProjection = that.getMapProjection();

    that.layerContext.setTransform(1, 0, 0, 1, 0, 0);


    if( that.currentStepGeoLatLng != false && that.drawPointerLine == 1 && mapProjection != false ) {

      //var scale = Math.pow(2, that.options.map.zoom) * window.devicePixelRatio || 1; // new style
      var scale = Math.pow(2, that.options.map.zoom); // new style
      that.layerContext.scale(scale, scale);
      var offset = mapProjection.fromLatLngToPoint(that.canvasLayer.getTopLeft());
      that.layerContext.translate(-offset.x, -offset.y);

      var rectLatLng = new google.maps.LatLng( that.currentStepGeoLatLng.lat, that.currentStepGeoLatLng.lng);

      var originPoint = mapProjection.fromLatLngToPoint(rectLatLng);
      //that.layerContext.fillRect(worldPoint.x, worldPoint.y, 1, 1);

      var destPoint = mapProjection.fromLatLngToPoint(
        new google.maps.LatLng(
          that.options.map.getBounds().getNorthEast().lat(),
          that.options.map.getBounds().getSouthWest().lng()
        )
      );

      var destPointVariation = that.getCurrentStepDOMPositionOverMap();

      that.canvasLayer.canvas.style.zIndex = 30;
      // line
      that.layerContext.moveTo( originPoint.x, originPoint.y);
      that.layerContext.strokeStyle = that.options.lineColor;
      that.layerContext.lineWidth = that.options.lineWidth / scale;
      that.layerContext.lineTo( destPoint.x + destPointVariation.x/scale , destPoint.y + destPointVariation.y/scale );
      that.layerContext.stroke();
      that.layerContext.beginPath();


      // circle
      that.layerContext.fillStyle = that.options.lineColor;
      that.layerContext.arc( originPoint.x, originPoint.y, that.options.lineDotRadious/scale ,0,  2*Math.PI);
      that.layerContext.fill();
      that.layerContext.stroke();
      that.layerContext.beginPath();
    }

  },


  getCurrentStepDOMPositionOverMap: function() {
    var that = this;
    var stepDiv = $(that.currentStepDOM);

    var offset = stepDiv.position();
    var width = stepDiv.width();
    var height = stepDiv.height();

    return { x: offset.left + width  , y: offset.top- that.scrollPosition + height/4 };

  },


  latlngInNoPanZone: function( lat, lng ) {
    var that = this;

    var inside = false;

    var mb = that.getMapBounds();

    if( mb != false ) {
      var sw = mb[0];
      var ne = mb[1];

      var scale = Math.pow(2, that.options.map.getZoom());

      var swInner = new google.maps.Point(   that.coordToPixel(sw ).x+ that.options.noPanZone.left /scale,   that.coordToPixel(sw).y- that.options.noPanZone.bottom /scale );
      var neInner = new google.maps.Point(   that.coordToPixel(ne ).x- that.options.noPanZone.right /scale ,   that.coordToPixel(ne).y+ that.options.noPanZone.top /scale );

      var swI = that.options.map.getProjection().fromPointToLatLng( swInner );
      var neI = that.options.map.getProjection().fromPointToLatLng( neInner );

      if( lat < neI.lat() && lng < neI.lng() && lat > swI.lat() && lng > swI.lng() ) {
        inside = true;
      }
    }


    return inside;
  },

  coordToPixel: function( latLng) {
    var that = this;
    return that.options.map.getProjection().fromLatLngToPoint( latLng );
  },

  getMapBounds: function() {
    var that = this;
    var ret = false;
    if( typeof that.options.map.getBounds() != 'undefined' ) {
      ret = [ that.options.map.getBounds().getSouthWest(), that.options.map.getBounds().getNorthEast() ];
    }
    return ret;
  },

  getMapProjection: function() {
    var that = this;
    var ret = false;

    if( typeof that.options.map.getProjection() != 'undefined' ) {
      ret = that.options.map.getProjection();
    }

    return ret;
  }

});
