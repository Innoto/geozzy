var geozzy = geozzy || {};
if(!geozzy.storyComponents) geozzy.storyComponents={};

geozzy.storyComponents.StoryListView = Backbone.View.extend({
  displayType: 'list',
  parentStory: false,

  initialize: function( opts ) {
    var that = this;

    var options = new Object({
      container: false,
      steepMargins: 400, //false, will calculate from the sizes of story background display
      //tpl: geozzy.storyComponents.listViewTemplate,
      tplElement: geozzy.storyComponents.listElementTemplate
    });

    that.options = $.extend(true, {}, options, opts);

    that.tpl = _.template(that.options.tpl);
    that.tplElement = _.template(that.options.tplElement);
    that.el = that.options.container;
  },

  setParentStory: function( obj ) {
    var that = this;

    that.parentStory = obj;
  }

  render: function() {
    var that = this;

    // Calculate distances
    if( that.options.steepMargins ) {
      
    }
    else
    if( typeof that.parentStory.displays.background != 'undefined') {

    }

    that.$el.html('');
    that.parentStory.storyEvents.each( function( step , i ) {

      var data = step.toJSON();
      //data.marginTop =

      that.options.tplElement( data );
    });

  }

});
