var geozzy = geozzy || {};
if (! geozzy.storyComponents) { geozzy.storyComponents= {} }

geozzy.storyComponents.mainRouter = Backbone.Router.extend({
  parentStory: false,
  routes: {
    '': 'main',
    'resource/:id': 'resource'
  },


  main: function( ) {
    var that = this;
/*

    if( typeof that.parentStory.metricsResourceController != 'undefined') {
      that.parentStory.metricsResourceController.eventAccessedEnd();
    }
    that.parentStory.options.resourceQuit();*/
  },

  resource: function( id ) {
    var that = this;

    that.parentStory.options.resourceAccess(id);
  }

});
