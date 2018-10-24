var geozzy = geozzy || {};
if(!geozzy.storyComponents) geozzy.storyComponents={};


geozzy.storyComponents.StoryPluginLegendView = Backbone.View.extend({
  displayType: 'plugin',
  parentStory: false,
  tplElement: false,

  initialize: function( opts ) {
    var that = this;

    var options = new Object({
      container: false,
      tplElement: '<img class="img-fluid" src="'+cogumelo.publicConf.mediaHost+'cgmlImg/<%-id%>/storyLegend/<%-id%>.png" />'
    });
    that.options = $.extend(true, {}, options, opts);


    that.tplElement = _.template(that.options.tplElement);
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

    var legend = that.parentStory.storySteps.get(step.id).get('legend');

    if( legend != null ) {

      $(that.options.container).html(that.tplElement({id:legend}));
      $(that.options.container).fadeIn();
    }
    else {
      $(that.options.container).fadeOut();
    }


  }

});
