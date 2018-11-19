var geozzy = geozzy || {};
if (! geozzy.rextRoutes) { geozzy.rextRoutes= {}; }

geozzy.rextRoutes.routeCollection = Backbone.Collection.extend({
  url: '/api/routes',
  model: geozzy.rextRoutes.routeModel,

  fetchOne: function( restOfUrl, callback ) {
    var that = this;
    var col  = new geozzy.rextRoutes.routeCollection();
    col.url = '/api/routes' + restOfUrl;


    col.fetch({
      success: function(res){

        if( res.toJSON()[0].id  ) {
          that.add( res.toJSON()[0] );
          callback();

        }


      }
    });
  }
});
