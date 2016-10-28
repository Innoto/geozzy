var geozzy = geozzy || {};
if (! geozzy.storyComponents) { geozzy.storyComponents= {}; }

geozzy.travelPlannerComponents.mainRouter = Backbone.Router.extend({
  parentTp: false,
  routes: {
    '': 'main',
    'resource/:id': 'resource'
  },


  main: function( ) {
    var that = this;

    //that.parentStory.triggerEvent('loadMain');
  },

  resource: function( id ) {
    var that = this;

    that.parentTp.openResource(id);
  }

});
