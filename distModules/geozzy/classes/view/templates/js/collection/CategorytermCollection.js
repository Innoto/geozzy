
var geozzy = geozzy || {};
if(!geozzy.collection) geozzy.collection={};

geozzy.collection.CategorytermCollection = Backbone.Collection.extend({
  baseUrl: '/api/core/categoryterms',
  url: false,
  setUrlByIdName: function(idName) {
    lang = this.getLang();
    this.url = lang + this.baseUrl + '/id/false/idname/'+idName;
  },
  setUrlById: function(id) {
    lang = this.getLang();
    this.url = lang + this.baseUrl + '/id/'+id+'/idname/false';
  },

  getLang: function(){
    var lang = false;
    if (typeof(cogumelo.publicConf.C_LANG)!='undefined'){
      lang = '/'+cogumelo.publicConf.C_LANG;
    }
    return lang;
  },

  model: geozzy.model.TaxonomytermModel
});
