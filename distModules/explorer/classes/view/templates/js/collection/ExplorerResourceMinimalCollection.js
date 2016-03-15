
var geozzy = geozzy || {};
if (! geozzy.explorerComponents) { geozzy.explorerComponents= {} }

geozzy.explorerComponents.resourceMinimalCollection = Backbone.Collection.extend({
//var geozzy.explorerComponents.resourceMinimalCollection = Backbone.Collection.extend({
  url: false,
  model: ExplorerResourceMinimalModel


});
