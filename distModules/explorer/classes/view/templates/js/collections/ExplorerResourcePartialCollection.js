var ExplorerResourcePartialCollection = Backbone.Collection.extend({
  url: false,
  model: ExplorerResourcePartialModel,

  fetchAndCache: function( params ) {
    var that = this;
    var resPartialCollection = new ExplorerResourcePartialCollection();
    resPartialCollection.url = params.url;

    var tmpResources = resPartialCollection.fetch({
      data: { ids: [1,10,15] },
      type: 'POST',
      success: function( list ){
        list.each(function(resource) {
          that.add(resource);
        });
        if(params.success) {
          params.success();
        }        
      }
    });

  }

});
