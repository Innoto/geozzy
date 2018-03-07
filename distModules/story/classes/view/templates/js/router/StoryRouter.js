var geozzy = geozzy || {};
if (! geozzy.storyComponents) { geozzy.storyComponents= {}; }

geozzy.storyComponents.mainRouter = Backbone.Router.extend({
  parentStory: false,
  routes: {
    '': 'main'//,
    //'resource/:id': 'resource'
  },


  main: function( ) {
    var that = this;

    that.parentStory.triggerEvent('loadMain');
  },

  resource: function( id ) {
    var that = this;

    //that.parentStory.options.parentStory.resourceAccess(id);
    that.parentStory.triggerEvent('loadResource', id);
  }

});
