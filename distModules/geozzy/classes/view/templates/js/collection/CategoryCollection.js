var geozzy = geozzy || {};
if(!geozzy.collections) geozzy.collections={};

geozzy.collections.CategoryCollection = Backbone.Collection.extend({
  url: '/api/core/categorylist',
  model: geozzy.models.TaxonomygroupModel
});
