var geozzy = geozzy || {};
if(!geozzy.explorerComponents) geozzy.explorerComponents={};

geozzy.explorerComponents.routesCollectionInstance = false;


geozzy.explorerComponents.routesView = Backbone.View.extend({

  displayType: 'plugin',
  parentExplorer: false,

  template: false,

  visible: false,

  initialize: function( opts ) {
    var that = this;
    var options = new Object({
      showGraph: false,
      hoverGraphDiv: false,
      ShowRouteInZoomLevel: 10,
      routeResolution: 12,
      showMarkerStart: false,
      showMarkerEnd: false
    });

    that.options = $.extend(true, {}, options, opts);

  },

  setParentExplorer: function( parentExplorer ) {
    var  that = this;
    that.parentExplorer = parentExplorer;

    that.parentExplorer.bindEvent('zoomChanged', function(){ that.hideRoutes(); });

    that.parentExplorer.bindEvent('resourceHover', function( params ){

      //that.hideRoute();
      //alert(params.id)

      if( geozzy.explorerComponents.routesCollectionInstance === false ) {
        geozzy.explorerComponents.routesCollectionInstance = new geozzy.rextRoutes.routeCollection();
      }


      if( typeof( geozzy.explorerComponents.routesCollectionInstance.get( params.id ) ) != 'undefined' ){
        geozzy.explorerComponents.routesCollectionInstance.get( params.id ).get('routeViewInstance').showRoute();
      }
      else {
        //var routesCollectionProvisional = new geozzy.rextRoutes.routeCollection();
        //routesCollectionProvisional.url = '/api/routes/id/' + params.id + '/resolution/' + that.options.routeResolution;
        geozzy.explorerComponents.routesCollectionInstance.fetchOne(
          'id/' + params.id + '/resolution/' + that.options.routeResolution ,
          function(  ) {

            var r = geozzy.explorerComponents.routesCollectionInstance.get(params.id);


            var routeOpts = {
                  map: that.parentExplorer.displays.map.map,
                  routeModel: r,
                  showGraph: that.options.showGraph,
                  graphContainer: that.options.hoverGraphDiv ,
                  showLabels: false,
                  allowsTrackHover:false,
                  ShowRouteInZoomLevel: that.options.ShowRouteInZoomLevel,
                  drawXGrid: false,
                  drawYGrid: false,
                  pixelsPerLabel:100,
                  axisLineColor: 'transparent'
                };

            if( that.options.showMarkerStart == false ) {
              routeOpts.markerStart = false;
            }

            if( that.options.showMarkerEnd == false ) {
              routeOpts.markerEnd = false;
            }



            r.set('routeViewInstance', new geozzy.rextRoutes.routeView( routeOpts ));

            if( that.getLoadingPromise(params.id) ) {

              r.get('routeViewInstance').hideRoute();
            }


              //geozzy.explorerComponents.routesCollectionInstance.set( res.toJSON() );
              //cogumelo.log(geozzy.explorerComponents.routesCollectionInstance.get(params.id) );

        });
      }


    });


    that.parentExplorer.bindEvent('resourceMouseOut', function( params ){
      that.hideRoute( params.id);
    });


    that.parentExplorer.bindEvent('resourceClick', function( params ){
      //that.show(params.id);
    });

  },


  hideRoute: function( id ) {
    var that = this;

    var route = geozzy.explorerComponents.routesCollectionInstance.get(id);

    if( route &&  typeof route.get('routeViewInstance') != 'undefined') {
      route.get('routeViewInstance').hideRoute();
    }
    else {
      that.setLoadingPromise(id);
    }


  },


  hideRoutes: function() {
    var that = this;

    if(geozzy.explorerComponents.routesCollectionInstance) {
      geozzy.explorerComponents.routesCollectionInstance.each(  function(e,i){
        e.get('routeViewInstance').hideRoute();
      });
    }
  },


  hideLoadingPromises: [],
  setLoadingPromise: function( id ) {
    var that = this;

    that.hideLoadingPromises.push(id);
  },

  getLoadingPromise: function(id) {
    var that = this;
    var endLoadingPromises = [];
    var found = false;

    $( that.hideLoadingPromises ).each( function(i,e){
      if(e == id) {

        found = true;
      }
      else {
        endLoadingPromises.push(e);
      }
    });

    that.hideLoadingPromises = endLoadingPromises;

    return found;
  }





});
