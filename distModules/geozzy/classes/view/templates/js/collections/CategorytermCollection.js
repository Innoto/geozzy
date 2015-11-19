var geozzy = geozzy || {};
if(!geozzy.collections) geozzy.collections={};

geozzy.collections.CategorytermCollection = Backbone.Collection.extend({
  baseUrl: '/core/categoryterms',
  url: false,
  setUrlByIdName: function(idName) {
    this.url = this.baseUrl + '/id/false/idname/'+idname;
  },
  setUrlById: function(id) {
    this.url = this.baseUrl + '/id/'+id+'/idname/false';
  },

  model: geozzy.models.TaxonomytermModel
});
