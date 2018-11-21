

var geozzy = geozzy || {};
if(!geozzy.storyComponents) geozzy.storyComponents={};


geozzy.storyComponents.StoryPluginKMLView = Backbone.View.extend({
  displayType: 'plugin',
  kmlLayers: [],
  initialize: function( opts ) {
    var that = this;

    var options = new Object({

    });
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

  setStep: function( step ) {
    var that = this;

    that.hideAll();
    var KMLID = that.parentStory.storySteps.get(step.id).get('KML');

    if( KMLID) {
      var urlKML = cogumelo.publicConf.site_host+'/cgmlformfilewd/'+KMLID+'/'+KMLID+'.kml';
      cogumelo.log(urlKML);
      if( typeof that.kmlLayers[ step.id ] === 'undefined'  ) {
        that.kmlLayers[ step.id ] = new google.maps.KmlLayer({
          url: urlKML,
          clickable:false,
          suppressInfoWindows: false,
          preserveViewport:true,
          zIndex: 2
        });
        that.kmlLayers[ step.id ].setMap( that.parentStory.displays.background.options.map );
      }
      else {
        that.kmlLayers[ step.id ].setMap( that.parentStory.displays.background.options.map );
      }
    }



  },

  hideAll : function() {
    var that = this;

    $.each( that.kmlLayers, function(i, layer) {
      if( typeof layer != 'undefined') {
        layer.setMap(null);
      }

    });
  }

});
