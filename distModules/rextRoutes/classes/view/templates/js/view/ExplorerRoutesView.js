var geozzy = geozzy || {};
if(!geozzy.explorerComponents) geozzy.explorerComponents={};

geozzy.explorerComponents.routesView = Backbone.View.extend({

  displayType: 'plugin',
  parentExplorer: false,

  template: false,

  routeView: false,

  initialize: function( opts ) {
    var that = this;
    var options = new Object({

    });

    that.options = $.extend(true, {}, options, opts);

    //that.template = _.template( that.options.tpl );
    //that.mousePosEventListener();



  },

  setParentExplorer: function( parentExplorer ) {
    var  that = this;
    that.parentExplorer = parentExplorer;

    that.parentExplorer.bindEvent('resourceHover', function( params ){
      //alert(params.id)
      that.hideRoute();
      var routesCollection = new geozzy.rextRoutes.routeCollection();

      routesCollection.url = '/api/routes/id/' + params.id + '/resolution/90'
      routesCollection.fetch({
        showGraph:false,
        success: function( res ) {

          that.routeView = new geozzy.rextRoutes.routeView({
            map: that.parentExplorer.displays.map.map,
            routeModel: routesCollection.get( params.id ),
            showGraph: true
          });
        }
      });
    });

    that.parentExplorer.bindEvent('resourceMouseOut', function( params ){
      //that.hide(params.id);
      that.hideRoute();
    });


    that.parentExplorer.bindEvent('resourceClick', function( params ){
      //that.show(params.id);
    });

  },

  hideRoute: function() {
    var that = this;
    if( that.routeView !== false ) {
      that.routeView.hideRoute();
      that.routeView = false;
    }
  }



});
