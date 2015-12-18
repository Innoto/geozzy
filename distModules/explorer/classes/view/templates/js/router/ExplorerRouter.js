var ExplorerRouter = Backbone.Router.extend({
  parentExplorer: false,
  routes: {
    '': 'main',
    'resource/:id': 'resource'
  },

  main: function( ) {
    var that = this;

    that.parentExplorer.options.resourceQuit();
  },

  resource: function( id ) {
    var that = this;

    that.parentExplorer.options.resourceAccess(id);
  }

});
