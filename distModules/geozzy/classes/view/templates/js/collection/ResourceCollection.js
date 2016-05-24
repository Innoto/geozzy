var geozzy = geozzy || {};
if(!geozzy.collection) geozzy.collection={};

geozzy.collection.ResourceCollection = Backbone.Collection.extend({
  baseUrl: '/api/core/resourcelist',
  url: false,
  model: geozzy.model.ResourceModel,
  //sortKey: 'weight',

  options: {
    fields: false,
    filters: false,
    rtype: false,
    rextmodels: false,
    category: false,
    collection: false,
    updatedfrom: false,
    urlAlias: false
  },

  initialize: function( opts ) {


    var that = this;

    that.options = $.extend(true, {}, that.options, opts);

    that.url = that.baseUrl;

    that.url += '/fields/'+that.options.fields
    that.url += '/filters/'+that.options.filters
    that.url += '/rtype/'+that.options.rtype
    that.url += '/rextmodels/'+that.options.rextmodels
    that.url += '/category/'+that.options.category
    that.url += '/collection/'+that.options.collection
    that.url += '/updatedfrom/'+that.options.updatedfrom
    that.url += '/urlAlias/'+that.options.urlAlias
  },


  fetchByIds: function( idsArray, fetchCallback ) {
    var that = this;

    that.fetch({
      type: 'POST',
      data:{ids: idsArray},
      success: function( list ) {
        fetchCallback();
      }
    });


  }

});
