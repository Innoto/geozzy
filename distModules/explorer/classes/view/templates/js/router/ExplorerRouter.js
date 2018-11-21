var geozzy = geozzy || {};
if (! geozzy.explorerComponents) { geozzy.explorerComponents= {}; }

geozzy.explorerComponents.mainRouter = Backbone.Router.extend({
  parentExplorer: false,
  routes: {
    '': 'explorerMain'
  },


  explorerMain: function( ) {
    var that = this;

    that.parentExplorer.triggerEvent('resourceQuit', {});
  }

});
