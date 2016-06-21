var geozzy = geozzy || {};
if (! geozzy.rextRoutes) { geozzy.rextRoutes= {} }

geozzy.rextRoutes.routeCollection = Backbone.Collection.extend({
  url: '/api/routes',
  model: geozzy.rextRoutes.routeModel
});
