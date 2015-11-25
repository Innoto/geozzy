var geozzy = geozzy || {};
if(!geozzy.collection) geozzy.collection={};

geozzy.collection.CategorytermCollection = Backbone.Collection.extend({
  baseUrl: '/api/core/categoryterms',
  url: false,
  setUrlByIdName: function(idName) {
    this.url = this.baseUrl + '/id/false/idname/'+idName;
  },
  setUrlById: function(id) {
    this.url = this.baseUrl + '/id/'+id+'/idname/false';
  },

  model: geozzy.model.TaxonomytermModel
});
