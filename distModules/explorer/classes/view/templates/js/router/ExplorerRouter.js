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

    that.parentExplorer.triggerEvent('resourceQuit', {});
  },

  resource: function( id ) {
    var that = this;
    that.parentExplorer.triggerEvent('resourceAccess', {id: id});
    //that.parentExplorer.options.resourceAccess(id);
    if(that.parentExplorer.explorerTouchDevice) {
      that.parentExplorer.triggerEvent('resourceMouseOut', {id:0});
    }
  }

});
