/*var geozzy = geozzy || {};
if(!geozzy.adminStoryComponents) geozzy.adminStoryComponents={};

geozzy.adminStoryComponents.ListStoryView = Backbone.View.extend({

  el : $("#side-menu .storiesList"),
  tagName : '',
  stories : false,
  listStoryItemTemplate : false,

  events: { },

  initialize: function(  ) {
    var that = this;
    that.stories = new geozzy.storyComponents.StoryStepCollection();
  },

  render: function() {
    var that = this;
    that.$el.html('');
    that.listStoryItemTemplate =  _.template( geozzy.adminStoryComponents.StoryTemplate );

    _.each( that.stories.toJSON() , function(item){
      //cogumelo.log( that.listStoryItemTemplate(item))
      $(that.el).append( that.listStoryItemTemplate(item) );
    });
  }

});
*/
