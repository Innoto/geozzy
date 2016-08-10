var geozzy = geozzy || {};
if(!geozzy.storyComponents) geozzy.storyComponents={};

geozzy.storyComponents.ListStoryView = Backbone.View.extend({

  el : $("#side-menu .storiesList"),
  tagName : '',
  stories : false,
  listStoryItemTemplate : false,

  events: { },

  initialize: function(  ) {
    var that = this;
    that.stories = new geozzy.storyComponents.StoryCollection();
  },

  render: function() {
    var that = this;
    that.$el.html('');
    that.listStoryItemTemplate =  _.template( geozzy.storyComponents.StoryTemplate );
    _.each( that.stories.toJSON() , function(item){
      that.$el.append( that.listStoryItemTemplate(item) );
    });
  }

});
