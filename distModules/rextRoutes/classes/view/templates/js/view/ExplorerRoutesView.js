var geozzy = geozzy || {};
if(!geozzy.explorerComponents) geozzy.explorerComponents={};

geozzy.explorerComponents.routesView = Backbone.View.extend({

  displayType: 'plugin',
  parentExplorer: false,

  template: false,

  routeView: false,
  visible: false,

  initialize: function( opts ) {
    var that = this;
    var options = new Object({
      showGraph: false,
      hoverGraphDiv: false
    });

    that.options = $.extend(true, {}, options, opts);

  },

  setParentExplorer: function( parentExplorer ) {
    var  that = this;
    that.parentExplorer = parentExplorer;

    that.parentExplorer.bindEvent('resourceHover', function( params ){

      //that.hideRoute();
      //alert(params.id)
      var routesCollection = new geozzy.rextRoutes.routeCollection();

      routesCollection.url = '/api/routes/id/' + params.id + '/resolution/90'
      that.fetchInstance = routesCollection.fetch({
        success: function( res ) {

          that.routeView = new geozzy.rextRoutes.routeView({
            map: that.parentExplorer.displays.map.map,
            routeModel: routesCollection.get( params.id ),
            showGraph: that.options.showGraph,
            graphContainer: that.options.hoverGraphDiv ,
            showLabels: false,
            markerEnd:false,
            allowsTrackHover:false
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

    console.log(that.fetchInstance)

    if( that.routeView !== false ) {
      that.routeView.hideRoute();
      that.routeView = false;
    }
  }



});
