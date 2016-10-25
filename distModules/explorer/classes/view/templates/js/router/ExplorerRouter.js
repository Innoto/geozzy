var geozzy = geozzy || {};
if (! geozzy.explorerComponents) { geozzy.explorerComponents= {} }

geozzy.explorerComponents.mainRouter = Backbone.Router.extend({
  parentExplorer: false,
  routes: {
    '': 'main',
    'resource/:id': 'resource'
  },


  main: function( ) {
    var that = this;

    that.parentExplorer.triggerEvent('resource_quit', {});
  },

  resource: function( id ) {
    var that = this;

    that.parentExplorer.options.resourceAccess(id);
  }

});
