var geozzy = geozzy || {};
if(!geozzy.explorerComponents) geozzy.explorerComponents={};



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
      //that.show(params.id);
    });

    that.parentExplorer.bindEvent('resourceMouseOut', function( params ){
      //that.hide(params.id);
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
        //  that.renderMapRoute();
        //  that.renderGraphRoute();
        }
      });
    }
    else {
      //that.renderMapRoute();
      //that.renderGraphRoute();
    }

  },




  renderMapRoute: function(){
    console.log('RENDERIZADO')
    var that = this;
    var recorrido = [ ];

    var route =  that.routesCollection.get(126)

console.log(route);

    var map = that.parentExplorer.displays.map.map ;

    $.each( route.get('trackPoints') , function(i,e){
      recorrido.push({id:i, lat: e[0], lng:e[1] });
    });



    var recorridoPolylineBK2 = new google.maps.Polyline({
      path: recorrido,
      geodesic: true,
      strokeOpacity: 0,
      strokeWeight: 20
    });

    var recorridoPolylineBK1 = new google.maps.Polyline({
      path: recorrido,
      geodesic: true,
      strokeOpacity: 0,
      strokeWeight: 10
    });


    var recorridoPolyline = new google.maps.Polyline({
      path: recorrido,
      geodesic: true,
      strokeColor: that.options.strokeColor,
      strokeOpacity: that.options.strokeOpacity,
      strokeWeight: that.options.strokeWeight
    });

    recorridoPolyline.setMap( map );
    recorridoPolylineBK1.setMap( map );
    recorridoPolylineBK2.setMap( map );

    recorridoPolylineBK2.addListener('mouseover', function(ev){
      findPoint(ev.latLng.lat(), ev.latLng.lng());
      isTrackHover = true;
    });
    recorridoPolylineBK1.addListener('mouseover', function(ev){
      findPoint(ev.latLng.lat(), ev.latLng.lng());
      isTrackHover = true;
    });
    recorridoPolyline.addListener('mouseover', function(ev){
      findPoint(ev.latLng.lat(), ev.latLng.lng());
      isTrackHover = true;
    });
/*
    recorridoPolylineBK2.addListener('mouseout', function(ev){
      outRecorrido();
    });
    recorridoPolylineBK1.addListener('mouseout', function(ev){
      outRecorrido();
    });
    recorridoPolyline.addListener('mouseout', function(ev){
      outRecorrido();
    });*/

  },

  renderGraphRoute: function() {
    /*
    var that = this;

    var chartString = "step,Altitude\n";
    $.each( route.trackPoints, function(i,e){
        chartString += i + "," + e[2] + "\n";
    });




    grafico = new Dygraph( document.getElementById("graph"),
      chartString
    );

    grafico.updateOptions( {
      annotationMouseOverHandler: function(annotation, point, dygraph, event) {
          alert('')
      }
    });

    $("#graph").mousemove(function(e) {
        var seleccionado = grafico.getSelection();

        that.hoverRoute( seleccionado )

    }).mouseleave(function(e) {
        var seleccionada = grafico.getSelection();
        outRecorrido();


    });*/
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
