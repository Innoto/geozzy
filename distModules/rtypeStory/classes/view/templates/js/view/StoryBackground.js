var geozzy = geozzy || {};
if(!geozzy.storyComponents) geozzy.storyComponents={};

geozzy.storyComponents.StoryBackgroundView = Backbone.View.extend({
  displayType: 'background',
  parentStory: false,
  blockSoftAnimation: false,

  scrollDirection: 1,
  scrollPosition:0,

  currentStepGeoLatLng: false,

  initialize: function( opts ) {
    var that = this;

    var options = new Object({
      map: false,
      drawLine:true,
      moveToStep:true
    });

    that.options = $.extend(true, {}, options, opts);

    $(window).on('scroll', function(){
      that.softAnimation( $(this) );
    } );


  },

  setParentStory: function( obj ) {
    var that = this;

    that.parentStory = obj;
  },

  render: function() {
    var that = this;

    that.parentStory.bindEvent('stepChange', function(obj){
      that.setStep(obj);
    });
    that.setCanvasLayer();
  },

  setStep: function( obj ) {
    var that = this;



    var step = that.parentStory.storySteps.get( obj.id );

    var loc = false;

    if( that.options.moveToStep === true && step.get('lat') && typeof step.get('lng') ) {

      that.currentStepGeoLatLng = { lat: step.get('lat'), lng: step.get('lng') };

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
      resizeHandler: function(){ that.resizeCanvasLayer() },
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
    // we like our rectangles hideous
    that.layerContext.fillStyle = 'rgba(230, 230, 26, 1)';


    console.log(canvasWidth);

    /* We need to scale and translate the map for current view.
     * see https://developers.google.com/maps/documentation/javascript/maptypes#MapCoordinates
     */
    var mapProjection = that.options.map.getProjection();
    /**
     * Clear transformation from last update by setting to identity matrix.
     * Could use that.layerContext.resetTransform(), but most browsers don't support
     * it yet.
     */
    that.layerContext.setTransform(1, 0, 0, 1, 0, 0);

    // scale is just 2^zoom
    // If canvasLayer is scaled (with resolutionScale), we need to scale by
    // the same amount to account for the larger canvas.
    var scale = Math.pow(2, that.options.map.zoom) * window.devicePixelRatio || 1;
    that.layerContext.scale(scale, scale);
    /* If the map was not translated, the topLeft corner would be 0,0 in
     * world coordinates. Our translation is just the vector from the
     * world coordinate of the topLeft corder to 0,0.
     */
    var offset = mapProjection.fromLatLngToPoint(that.canvasLayer.getTopLeft());
    that.layerContext.translate(-offset.x, -offset.y);
    // project rectLatLng to world coordinates and draw

    if( that.currentStepGeoLatLng != false ) {
      var rectLatLng = new google.maps.LatLng( that.currentStepGeoLatLng.lat, that.currentStepGeoLatLng.lng);
      var rectWidth = 6.5;
      var worldPoint = mapProjection.fromLatLngToPoint(rectLatLng);
      //that.layerContext.fillRect(worldPoint.x, worldPoint.y, 1, 1);

      that.layerContext.moveTo(worldPoint.x, worldPoint.y);
      that.layerContext.lineWidth = 0.001;
      that.layerContext.lineTo(worldPoint.x+10, worldPoint.x-10);
    }

    that.layerContext.stroke();
    that.layerContext.beginPath();
  }


});
