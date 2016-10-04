var geozzy = geozzy || {};
if(!geozzy.storyComponents) geozzy.storyComponents={};


geozzy.storyComponents.StoryPluginLegendView = Backbone.View.extend({
  displayType: 'plugin',
  parentStory: false,

  initialize: function( opts ) {
    /*var options = new Object({
    });
    that.options = $.extend(true, {}, options, opts);*/
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
    console.log('Leyenda', step);
  }

});
