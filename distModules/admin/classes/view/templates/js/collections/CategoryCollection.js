var CategoryCollection = Backbone.Collection.extend({
  url: '/admin/categories',
	model: TaxonomygroupModel
});