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


    if( idsToFetch.length > 0) {
      var tmpResources = resPartialCollection.fetch({
        data: {ids: idsToFetch},
        type: 'POST',
        success: function( list ) {
          list.each(function(resource) {
            that.add(resource);
          });
          if(params.success) {
            params.success();
          }
        }
      });
    }
    else {
      params.success();
    }
  }

});
