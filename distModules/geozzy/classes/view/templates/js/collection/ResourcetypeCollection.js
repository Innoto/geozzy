var geozzy = geozzy || {};
if(!geozzy.collection) geozzy.collection={};

geozzy.collection.ResourcetypeCollection = Backbone.Collection.extend({
  url: '/api/core/resourcetypes',
  model: geozzy.model.ResourcetypeModel
});
