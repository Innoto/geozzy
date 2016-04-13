var geozzy = geozzy || {};
if (! geozzy.explorerComponents) { geozzy.explorerComponents= {} }

geozzy.explorerComponents.routeCollection = Backbone.Collection.extend({
  url: '/api/routes',
  model: geozzy.explorerComponents.routeModel
});
