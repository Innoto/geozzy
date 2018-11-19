var geozzy = geozzy || {};
if (! geozzy.explorerComponents) { geozzy.explorerComponents= {}; }

geozzy.explorerComponents.resourceMinimalCollection = Backbone.Collection.extend({
  url: false,
  model: geozzy.explorerComponents.resourceMinimalModel
});
