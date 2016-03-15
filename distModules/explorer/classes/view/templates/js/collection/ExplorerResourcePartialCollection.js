var geozzy = geozzy || {};
if (! geozzy.explorerComponents) { geozzy.explorerComponents= {} }


geozzy.explorerComponents.resourcePartialCollection = Backbone.Collection.extend({
  url: false,
  model: geozzy.explorerComponents.resourcePartialModel,

  fetchAndCache: function( params ) {

    var that = this;
    var resPartialCollection = new geozzy.explorerComponents.resourcePartialCollection();
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

          //console.log('Elementos cargados - Total:' + that.length, 'Engadidos: '+list.length)
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
