var geozzy = geozzy || {};
if(!geozzy.collection) geozzy.collection={};

geozzy.collection.ResourceCollection = Backbone.Collection.extend({
  baseUrl: '/api/resource',
  url: false,
  model: geozzy.model.ResourceModel,
  sortKey: 'weight'
});
