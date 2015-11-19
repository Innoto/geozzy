var geozzy = geozzy || {};
if(!geozzy.collections) geozzy.collections={};

geozzy.collections.ResourceCollection = Backbone.Collection.extend({
  baseUrl: '/api/resource',
  url: false,
  model: geozzy.models.ResourceModel,
  sortKey: 'weight'
});
