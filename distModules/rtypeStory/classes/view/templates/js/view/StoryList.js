var geozzy = geozzy || {};
if(!geozzy.storyComponents) geozzy.storyComponents={};

geozzy.storyComponents.StoryListView = Backbone.View.extend({
  displayType: 'list',
  parentStory: false,
  stepsDOM: false,
  initialize: function( opts ) {
    var that = this;

    var options = new Object({
      container: false,
      steepMargins: 400, //false, will calculate from the sizes of story background display
      //tpl: geozzy.storyComponents.listViewTemplate,
      tplElement: geozzy.storyComponents.listElementTemplate
    });

    that.options = $.extend(true, {}, options, opts);


    that.tplElement = _.template(that.options.tplElement);

    that.el = that.options.container;
    that.$el = $(that.el);

  },

  setParentStory: function( obj ) {
    var that = this;

    that.parentStory = obj;
  },

  render: function() {
    var that = this;

    // Calculate distances
    if( that.options.steepMargins ) {

    }
    else
    if( typeof that.parentStory.displays.background != 'undefined') {

    }


    that.$el.html('');
    that.parentStory.storySteps.each( function( step , i ) {
      var d = step.toJSON();
      //data.marginTop =
      that.$el.append( that.tplElement( d ) );
    });

    that.stepsDOM= that.$el.find('.storyStep').toArray();
    that.caculatePositions();
  },

  caculatePositions: function() {
    var that = this;
    $(that.stepsDOM ).each( function(i,e) {
        console.log(i, that.isVisible(e) );
    });
  },

  isVisible: function(elem){

      var docViewTop = $(window).scrollTop();
      var docViewBottom = docViewTop + $(window).height();

      var elemTop = $(elem).offset().top;
      var elemBottom = elemTop + $(elem).height();

      return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
  }

});
