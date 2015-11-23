var geozzy = geozzy || {};
if(!geozzy.collection) geozzy.collection={};

geozzy.collection.CategoryCollection = Backbone.Collection.extend({
  url: '/api/core/categorylist',
  model: geozzy.model.TaxonomygroupModel
});
