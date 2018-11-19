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

    that.url = '/'+cogumelo.publicConf.C_LANG+that.baseUrl;

    that.url += '/fields/'+that.options.fields;
    that.url += '/filters/'+that.options.filters;
    that.url += '/rtype/'+that.options.rtype;
    that.url += '/rextmodels/'+that.options.rextmodels;
    that.url += '/category/'+that.options.category;
    that.url += '/collection/'+that.options.collection;
    that.url += '/updatedfrom/'+that.options.updatedfrom;
    that.url += '/urlAlias/'+that.options.urlAlias;
  },

  filterById: function(idArray) {
    res = _.filter(this.toJSON(), function(r) {
      return $.inArray(r.id.toString(), idArray) != -1;
    }, this);

/*
    ids = _.filter(idArray, function(id) {
      return this.get(id);
    }, this);

    res = _.map(ids, function(id) {
      return this.get(id);
    }, this);
*/
    return(res);
  },

  fetchByIds: function( idsArray, fetchCallback ) {
    var that = this;

    return that.fetch({
      type: 'POST',
      data:{ids: idsArray},
      success: function( list ) {
        fetchCallback();
      }
    });


  }

});
