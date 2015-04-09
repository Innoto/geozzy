var CategorytermCollection = Backbone.Collection.extend({
  url: '/admin/categoryterms',
  model: TaxonomytermModel
});