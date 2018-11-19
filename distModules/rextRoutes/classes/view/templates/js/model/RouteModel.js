var geozzy = geozzy || {};
if (! geozzy.rextRoutes) { geozzy.rextRoutes= {}; }

geozzy.rextRoutes.routeModel = Backbone.Model.extend({
  defaults: {
    id: false,
    centroid: false,
    trackPoints: []
  }

});
