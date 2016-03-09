var geozzy = geozzy || {};
if(!geozzy.collection) geozzy.collection={};

geozzy.collection.UserSessionCollection = Backbone.Collection.extend({
  baseUrl: '/api/userSession',
  url: false,
  model: geozzy.model.UserModel,
  sortKey: 'weight'
});
