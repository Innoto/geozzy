var StarredCollection = Backbone.Collection.extend({
  url: '/api/admin/starred',
  model: TaxonomytermModel
});