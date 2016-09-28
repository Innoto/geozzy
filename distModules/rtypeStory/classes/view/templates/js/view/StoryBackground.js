var geozzy = geozzy || {};
if(!geozzy.storyComponents) geozzy.storyComponents={};

geozzy.storyComponents.StoryBackgroundView = Backbone.View.extend({
  displayType: 'background',
  parentStory: false,
  blockSoftAnimation: false,
  scrollPosition:0,
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
  },

  setStep: function( obj ) {
    var that = this;



    var step = that.parentStory.storySteps.get( obj.id );

    var loc = false;

    if( that.options.moveToStep === true && step.get('lat') && typeof step.get('lng') ) {

      that.blockSoftAnimation = true;

      if( that.options.map.getZoom() > step.get('defaultZoom') ) {
        that.options.map.setZoom(step.get('defaultZoom'));
      }
      window.setTimeout(function(){
        that.options.map.panTo( new google.maps.LatLng( step.get('lat'), step.get('lng') ) );
      }, 400);

      if( that.options.map.getZoom() <= step.get('defaultZoom') ) {
        window.setTimeout(function(){
          that.options.map.setZoom(step.get('defaultZoom'));
          that.blockSoftAnimation = false;
        }, 900);
      }
    }
  },

  softAnimation: function( scroll ) {

    var that = this;

    if( that.blockSoftAnimation === false ) {

      if( scroll.scrollTop() > that.scrollPosition ) {
        var direction = 1;
      }
      else {
        var direction = -1;
      }

      that.scrollPosition = scroll.scrollTop();
      that.options.map.panBy( 1 * direction, 0);
    }

  },

  DrawStepLine: function() {

  }

});
