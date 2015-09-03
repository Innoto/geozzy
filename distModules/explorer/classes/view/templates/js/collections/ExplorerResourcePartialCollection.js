var ExplorerResourcePartialCollection = Backbone.Collection.extend({
  url: false,
  model: ExplorerResourcePartialModel,

  fetchAndCache: function( params ) {

    var that = this;
    var resPartialCollection = new ExplorerResourcePartialCollection();
    resPartialCollection.url = params.url;

    var idsToFetch = [];
    $.each( params.ids, function(i,e) {
      if( !that.get( e ) ){
        idsToFetch.push( e );
      }
    });

    var tmpResources = resPartialCollection.fetch({
      data: {ids: params.ids},
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
