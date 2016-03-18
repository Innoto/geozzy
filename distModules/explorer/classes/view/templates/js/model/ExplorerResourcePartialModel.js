var geozzy = geozzy || {};
if (! geozzy.explorerComponents) { geozzy.explorerComponents= {} }

geozzy.explorerComponents.resourcePartialModel = Backbone.Model.extend({
  defaults: {
    id: false,
    title: false,
    description: false
  }

});
