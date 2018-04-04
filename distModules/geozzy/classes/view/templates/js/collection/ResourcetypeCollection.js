var geozzy = geozzy || {};
if(!geozzy.collection) geozzy.collection={};

geozzy.collection.ResourcetypeCollection = Backbone.Collection.extend({
  baseUrl: '/api/core/resourcetypes',
  url: false,
  model: geozzy.model.ResourcetypeModel,

  initialize: function( ) {
    var that = this;
    that.url = '/'+cogumelo.publicConf.C_LANG+that.baseUrl;
  }
});
