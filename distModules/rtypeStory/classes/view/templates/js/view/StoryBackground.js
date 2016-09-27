var geozzy = geozzy || {};
if(!geozzy.storyComponents) geozzy.storyComponents={};

geozzy.storyComponents.StoryBackgroundView = Backbone.View.extend({
  displayType: 'background',
  parentStory: false,
  stepsDOM: false,
  currentStep: false,
  initialize: function( opts ) {
    var that = this;

    var options = new Object({
      map: false,
      topMargin: 100,
      bottomMargin:300,
      leftMargin:30,
      rightMargin:20,
      steepMarginsDifference: 160,

      //tpl: geozzy.storyComponents.listViewTemplate,
      tplElement: geozzy.storyComponents.listElementTemplate
    });

    that.options = $.extend(true, {}, options, opts);


    that.tplElement = _.template(that.options.tplElement);

    that.el = that.options.container;
    that.$el = $(that.el);

    $(window).on('scroll', function(){ that.updateVisibleStep()} );

  }
});
