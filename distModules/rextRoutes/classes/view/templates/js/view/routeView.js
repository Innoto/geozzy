var geozzy = geozzy || {};
if(!geozzy.rextRoutes) geozzy.rextRoutes={};


geozzy.rextRoutes.routeView = Backbone.View.extend({

  options: false,
  trackMarker: false,
  grafico: false,
  events: {
    //  "click .explorerListPager .next" : "nextPage"
  },

  initialize: function( opts ) {
    var that = this;
    var options = new Object({
      map: false,
      ShowRouteInZoomLevel: 0,
      showGraph: false,
      graphContainer:false,
      strokeColor:  cogumelo.publicConf.rextRoutesConf.strokeColor,
      strokeBorderColor: cogumelo.publicConf.rextRoutesConf.strokeBorderColor,
      strokeOpacity: cogumelo.publicConf.rextRoutesConf.strokeOpacity,
      strokeWeight: cogumelo.publicConf.rextRoutesConf.strokeWeight,
      strokeBorderWeight: cogumelo.publicConf.rextRoutesConf.strokeBorderWeight,

      axisLineWidth:2,

      axisLabelColor: cogumelo.publicConf.rextRoutesConf.axisLabelColor,
      axisLineColor: cogumelo.publicConf.rextRoutesConf.axisLineColor,

      routeModel: false,
      markerStart: cogumelo.publicConf.rextRoutesConf.markerStart,
      markerEnd: cogumelo.publicConf.rextRoutesConf.markerEnd,
      drawXGrid: true,
      drawYGrid: true,
      showLabels: true,
      pixelsPerLabel: 15,
      allowsTrackHover: true
      //tpl: geozzy.explorerComponents.routesViewTemplate ,

    });
    that.options = $.extend(true, {}, options, opts);

    that.render();
  },

  render: function() {
    var that = this;

    if( that.options.routeModel !== false ) {


      if(that.options.ShowRouteInZoomLevel <= that.options.map.getZoom()) {
        that.renderMapRoute();
      }


      if( that.options.showGraph ) {
        that.renderGraphRoute();
      }

    }

  },




  renderMapRoute: function(){
    var that = this;



    var route = that.options.routeModel;

    var routeMap = {};
    var routeData = [ ];

    $.each( route.get('trackPoints') , function(i,e){
      routeData.push({id:i, lat: e[0], lng:e[1] });
    });



    routeMap.id = route.get('id');

    //cogumelo.log(route.get('trackPoints')[route.get('trackPoints').length - 1]);


    if( that.options.markerStart ) {
      routeMap.markerStart = marker = new google.maps.Marker({
        position:  {lat: route.get('trackPoints')[0][0], lng: route.get('trackPoints')[0][1]},
        title: __('Route start'),
        icon: {
          url: cogumelo.publicConf.media +  that.options.markerStart.img  , //'/module/rextRoutes/img/markerStart.png'
          anchor: new google.maps.Point( that.options.markerStart.anchor[0], that.options.markerStart.anchor[1] ) // 3,40
        },
        map: that.options.map
      });
    }

    if( route.get('circular') !== 1 && that.options.markerEnd ) {
      routeMap.markerEnd = marker = new google.maps.Marker({
        position: { lat: route.get('trackPoints')[route.get('trackPoints').length - 1][0], lng: route.get('trackPoints')[route.get('trackPoints').length - 1][1] },
        title: __('Route End'),
        icon: {
          url: cogumelo.publicConf.media + that.options.markerEnd.img, //'/module/rextRoutes/img/markerEnd.png'
          anchor: new google.maps.Point(  that.options.markerEnd.anchor[0],   that.options.markerEnd.anchor[1] ) //// 3,40
        },
        map: that.options.map
      });
    }

    that.polyline = new google.maps.Polyline({
      path: routeData,
      geodesic: true,
      strokeColor: that.options.strokeColor,
      strokeOpacity: that.options.strokeOpacity,
      strokeWeight: that.options.strokeWeight,
      zIndex:3
    });

    that.polylineBG1 = new google.maps.Polyline({
      path: routeData,
      geodesic: true,
      strokeOpacity: 0,
      strokeWeight: 20,
      zIndex:2
    });

    that.polylineBG2 = new google.maps.Polyline({
      path: routeData,
      geodesic: true,
      strokeColor: that.options.strokeBorderColor,
      strokeOpacity: that.options.strokeOpacity,
      strokeWeight: that.options.strokeBorderWeight,
      zIndex:1
    });


    that.mapRoute = routeMap;
    that.polylineBG2.setMap( that.options.map );
    that.polylineBG1.setMap( that.options.map );
    that.polyline.setMap( that.options.map );









    if( that.options.allowsTrackHover ) {

      that.polylineBG2.addListener('mouseover', function(ev){
        that.findPoint(ev.latLng.lat(), ev.latLng.lng());
        isTrackHover = true;
      });
      that.polylineBG1.addListener('mouseover', function(ev){
        that.findPoint(ev.latLng.lat(), ev.latLng.lng());
        isTrackHover = true;
      });
      that.polyline.addListener('mouseover', function(ev){
        that.findPoint(ev.latLng.lat(), ev.latLng.lng());
        isTrackHover = true;
      });


      that.polylineBG2.addListener('mouseout', function(ev){
        that.outRecorrido();
      });
      that.polylineBG1.addListener('mouseout', function(ev){
        that.outRecorrido();
      });
      that.polyline.addListener('mouseout', function(ev){
        that.outRecorrido();
      });
    }

  },


  renderGraphRoute: function( forceReload ) {

    var that = this;
    var route = that.options.routeModel;

//    var divName =







    if( !that.grafico || forceReload == true ) {



      var chartString = "step,"+__('Altitude')+"\n";
      $.each( route.get('trackPoints'), function(i,e){
          chartString += i + "," + e[2] + "\n";
      });

      if( that.options.graphContainer === false) {
        if( $('.resourceRouteGraphContainer').length == 0 ) {
          var controlUI = document.createElement('div');
          controlUI.title = 'Click to recenter the map';
          controlUI.className = 'resourceRouteGraphContainer';
          controlUI.innerHTML = '<div class="resourceRouteGraphLegend" style="display:none;"></div><div class="resourceRouteGraph"></div>';
          that.options.map.controls[google.maps.ControlPosition.BOTTOM_LEFT].push(controlUI);

        }
        that.containerGraph = '.resourceRouteGraph';
      }
      else {
        that.containerGraph = that.options.graphContainer;
      }

      var graphOptions = {
        // options go here. See http://dygraphs.com/options.html
        axisLineWidth: that.options.axisLineWidth,

        fillGraph: true,
        strokeWidth: 2,
        fillAlpha: 0.6,
        drawXGrid: that.options.drawXGrid,
        drawYGrid: that.options.drawYrid,
        pixelsPerLabel: that.options.pixelsPerLabel,

        highlightCircleSize: 5,
        drawHighlightPointCallback: Dygraph.Circles.CIRCLE,

        axisLabelColor: that.options.axisLabelColor,
        axisLineColor: that.options.axisLineColor,

        //labels:["step", "Altitude"],
        colors: [ that.options.strokeColor  ],
        axisLabelFontSize: 12,
        hideOverlayOnMouseOut: true,
        legend: 'follow',

        axes: {
          x: {
              axisLabelFormatter: function (x) {
                  return '';
              },
              valueFormatter: function (x) {
                  return '';
              }
          },
          y: {
             axisLabelFormatter: function (y) {
                 return '<b>' + y  + ' m </b>';
             },
             valueFormatter: function (y) {
                 return '<b>' + y  + ' m </b>';
             }
          }
        },

        highlightCallback: function(e, x, pts, row) {
          //cogumelo.log(route.get('trackPoints')[x][2]);
          $($(".resourceRouteGraphLegend")[0]).html( route.get('trackPoints')[x][2] +' m');
          $($(".resourceRouteGraphLegend")[0]).show();
        },

        unhighlightCallback: function(e) {
          $($(".resourceRouteGraphLegend")[0]).hide();
        }

      };


      if( that.options.showLabels != true) {
        graphOptions.yAxisLabelWidth = 0;
      }



      // wait until graph container exist
      var checkExist = setInterval(function() {
         if ($(that.containerGraph).length) {

           that.grafico = new Dygraph( $(that.containerGraph)[0] , chartString, graphOptions );

           that.grafico.updateOptions( {
             annotationMouseOverHandler: function(annotation, point, dygraph, event) {
             }
           });
           $($(that.containerGraph)[0]).mousemove(function(e) {
               var seleccionado = that.grafico.getSelection();
               that.hoverRoute( seleccionado );
           }).mouseleave(function(e) {
               var seleccionada = that.grafico.getSelection();
               that.outRecorrido();
           });


            clearInterval(checkExist);
         }
      }, 5);

    }



  },

/*
  showRoute: function( id ) {

    var that = this;
    var map = that.options.map ;

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
    var map = that.options.map ;

    $.each(  that.mapRoutes, function(i,e) {
      e.polyline.setMap( null );
      e.polylineBG1.setMap(map);
      e.polylineBG2.setMap(map);
      e.markerStart.setMap( null )
      e.markerEnd.setMap( null )
    });
  },

*/





  outRecorrido: function( ) {
    var that = this;

    if( that.trackMarker ) {
      that.trackMarker.setMap(null);
    }

  },



  findPoint: function(lat, lng) {
    var that = this;
    var route = that.options.routeModel;

    var lessDistance = false;
    var lessDistanceElementId = false;

    $.each( route.get('trackPoints') , function(i,e){

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
    var route = that.options.routeModel;
    var map = that.options.map;

    //grafico.setSelection(id);
    if( that.grafico ) {
      that.grafico.setSelection(id) ;
    }

    var scale = Math.pow(2, map.getZoom());

    if( typeof route.get('trackPoints') != 'undefined' ) {


      if(that.trackMarker === false) {
        that.trackMarker = new google.maps.Marker({
              position: {lat: route.get('trackPoints')[id][0] , lng: route.get('trackPoints')[id][1]},
              icon: {
                url: cogumelo.publicConf.media + '/module/rextRoutes/img/markerTrack.png' ,
                anchor: new google.maps.Point(10,27)
              },
              zIndex: 4,
              map: that.options.map
          });

        that.trackMarker.addListener('mouseover', function() {
          //that.trackMarker.setMap(null);
        });
      }
      else {
        that.trackMarker.setPosition({lat: route.get('trackPoints')[id][0] , lng: route.get('trackPoints')[id][1]});
        if( that.trackMarker.getMap() === null ) {
          that.trackMarker.setMap(that.options.map);
        }

      }

    }


  },

  mideBall: function( ) {
    var that = this;

    if( that.trackMarker ) {
      //that.trackMarker.setMap(null);
    }

  },


  showRoute: function() {
    var that = this;

    if(that.options.ShowRouteInZoomLevel <= that.options.map.getZoom()) {
      that.renderMapRoute();
    }

    if(that.options.showGraph) {
      that.renderGraphRoute(true);
    }

  },


  hideRoute: function() {
    var that = this;

    if( typeof that.mapRoute != 'undefined' ) {
      if( typeof that.mapRoute.markerStart  != 'undefined') {
        that.mapRoute.markerStart.setMap(null);
      }

      if( typeof that.mapRoute.markerEnd  != 'undefined') {
        that.mapRoute.markerEnd.setMap(null);
      }

      if( typeof that.mapRoute.markerEnd  != 'undefined') {
        that.mapRoute.markerEnd.setMap(null);
      }
    }

    if( typeof that.polyline != 'undefined') {
      that.polyline.setMap(null);
      that.polylineBG1.setMap(null);
      that.polylineBG2.setMap(null);
    }

  }
});
