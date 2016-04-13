var geozzy = geozzy || {};
if (! geozzy.explorerComponents) { geozzy.explorerComponents= {} }

geozzy.explorerComponents.routeModel = Backbone.Model.extend({
  defaults: {
    id: false,
    centroid: false,
    trackPoints: []
  }

});
