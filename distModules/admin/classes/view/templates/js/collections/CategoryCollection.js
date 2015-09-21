var CategoryCollection = Backbone.Collection.extend({
  url: '/api/admin/categories',
  model: TaxonomygroupModel
});
