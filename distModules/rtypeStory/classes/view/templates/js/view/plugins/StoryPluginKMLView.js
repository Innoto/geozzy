

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
    http://test2.geozzy.com/cgmlformfilewd/5734/monte-pindo-fervenza-do-ezaro-18-11-km.gpx
    that.hideAll();
    var KMLID = that.parentStory.storySteps.get(step.id).get('KML');

    if( KMLID) {
      var urlKML = cogumelo.publicConf.site_host+'/cgmlformfilewd/'+KMLID+'/'+KMLID+'.kml';
      console.log(urlKML);
      if( typeof that.kmlLayers[ step.id ] === 'undefined'  ) {
        that.kmlLayers[ step.id ] = new google.maps.KmlLayer({
          url: urlKML,
          clickable:false,
          suppressInfoWindows: false,
          preserveViewport:true
        });
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
