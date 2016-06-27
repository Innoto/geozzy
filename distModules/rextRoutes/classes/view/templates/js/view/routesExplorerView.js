var geozzy = geozzy || {};
if(!geozzy.explorerComponents) geozzy.explorerComponents={};


/*
geozzy.explorerComponents.routesView = Backbone.View.extend({



  tpl: false,


  displayType: 'plugin',
  parentExplorer: false,
  routesCollection: false,
  mapRoutes: [],
  trackCircle: false,

  events: {
    //  "click .explorerListPager .next" : "nextPage"
  },

  initialize: function( opts ) {
    var that = this;
    var options = new Object({
      strokeColor: '#000',
      strokeOpacity: 1,
      strokeWeight: 3
      //tpl: geozzy.explorerComponents.routesViewTemplate ,

    });
    that.options = $.extend(true, {}, options, opts);
  },

  setParentExplorer: function( parentExplorer ) {
    var  that = this;
    that.parentExplorer = parentExplorer;

    that.parentExplorer.bindEvent('resourceHover', function( params ){
      that.showRoute(params.id);
    });

    that.parentExplorer.bindEvent('resourceMouseOut', function( params ){
      that.hideRoutes();
    });


    that.parentExplorer.bindEvent('resourceClick', function( params ){
      //that.show(params.id);
    });
  },

  render: function() {
    var that = this;


    if( that.routesCollection === false ) {
      that.routesCollection = new geozzy.explorerComponents.routeCollection();
      that.routesCollection.fetch({
        success: function( res ) {
          that.renderMapRoutes();
          that.renderGraphRoutes();
        }
      });
    }

  },




  renderMapRoutes: function(){
    var that = this;
    that.mapRoutes = [];


    that.routesCollection.each( function(e,i){
      var route = e;


      var routeMap = {};
      var routeData = [ ];

      $.each( route.get('trackPoints') , function(i,e){
        routeData.push({id:i, lat: e[0], lng:e[1] });
      });



      routeMap.id = route.get('id');

      //console.log(route.get('trackPoints')[route.get('trackPoints').length - 1])

      routeMap.markerStart = marker = new google.maps.Marker({
        position:  {lat: route.get('trackPoints')[0][0], lng: route.get('trackPoints')[0][1]},
        title: __('Route start'),
        icon: {
          url: cogumelo.publicConf.media + '/module/rextRoutes/img/marker_start.png' ,
          anchor: new google.maps.Point(16,16)
        }
      });

      routeMap.markerEnd = marker = new google.maps.Marker({
        position: { lat: route.get('trackPoints')[route.get('trackPoints').length - 1][0], lng: route.get('trackPoints')[route.get('trackPoints').length - 1][1] },
        title: __('Route End'),
        icon: {
          url: cogumelo.publicConf.media + '/module/rextRoutes/img/marker_finish.png' ,
          anchor: new google.maps.Point(2,14)
        }

      });




      routeMap.polyline = new google.maps.Polyline({
        path: routeData,
        geodesic: true,
        strokeColor: that.options.strokeColor,
        strokeOpacity: that.options.strokeOpacity,
        strokeWeight: that.options.strokeWeight
      });

      routeMap.polylineBG1 = new google.maps.Polyline({
        path: routeData,
        geodesic: true,
        strokeOpacity: 0,
        strokeWeight: 20
      });

      routeMap.polylineBG2 = new google.maps.Polyline({
        path: routeData,
        geodesic: true,
        strokeOpacity: 0,
        strokeWeight: 10
      });


      that.mapRoutes.push( routeMap );

      //recorridoPolyline.setMap( map );









    });

  },

  renderGraphRoutes: function() {

  },


  showRoute: function( id ) {

    var that = this;
    var map = that.parentExplorer.displays.map.map ;

    $.each(  that.mapRoutes, function(i,e) {
      if( e.id == id ) {
        e.polyline.setMap( map );
        e.polylineBG1.setMap(map);
        e.polylineBG2.setMap(map);
        e.markerStart.setMap(map)
        e.markerEnd.setMap(map)
        return false;
      }
    });
  },

  hideRoutes: function( id ) {
    var that = this;
    var map = that.parentExplorer.displays.map.map ;

    $.each(  that.mapRoutes, function(i,e) {
      e.polyline.setMap( null );
      e.polylineBG1.setMap(map);
      e.polylineBG2.setMap(map);
      e.markerStart.setMap( null )
      e.markerEnd.setMap( null )
    });
  },

  findPoint: function(lat, lng) {
    var that = this;

    var lessDistance = false;
    var lessDistanceElementId = false;

    $.each( route.trackPoints , function(i,e){

      var currentDistance =  Math.pow( e[0] - lat, 2 ) + Math.pow( e[1] - lng, 2 )  ;


      if( lessDistance == false || lessDistance > currentDistance ) {
        lessDistance = currentDistance;
        lessDistanceElementId = i ;
      }
    });


    if( lessDistanceElementId ) {
      that.hoverRoute( lessDistanceElementId );
    }
  },


  hoverRoute: function( id ) {
    var that = this;

    //grafico.setSelection(id);
    grafico.setSelection(id) ;

    if( that.trackCircle ) {
      that.trackCircle.setMap(null);
    }

    var scale = Math.pow(2, map.getZoom());

    that.trackCircle = new google.maps.Circle({
        center: {lat: route.trackPoints[id][0] , lng: route.trackPoints[id][1]},
        radius: 850000/scale,
        strokeWeight:0,
        fillColor: "#0000FF",
        fillOpacity: 1,
        map: map
    });
  },

  mideBall: function( ) {
    var that = this;

    if( that.trackCircle ) {
      that.trackCircle.setMap(null);
    }

  }




});


*/
