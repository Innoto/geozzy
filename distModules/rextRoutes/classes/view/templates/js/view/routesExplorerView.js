var geozzy = geozzy || {};
if(!geozzy.explorerComponents) geozzy.explorerComponents={};



geozzy.explorerComponents.routesView = Backbone.View.extend({



  tpl: false,


  displayType: 'plugin',
  parentExplorer: false,
  routesCollection: false,
  mapRoutes: [],


  events: {
    //  "click .explorerListPager .next" : "nextPage"
  },

  initialize: function( opts ) {
    var that = this;
    var options = new Object({

      //tpl: geozzy.explorerComponents.routesViewTemplate ,

    });
    that.options = $.extend(true, {}, options, opts);

  },


  render: function() {
    var that = this;


    var recorrido = [ ];

    $.each(route.trackPoints, function(i,e){
    /*  var marker = new google.maps.Marker({
          position: new google.maps.LatLng( e[0], e[1] ),
          map: map,
          title: 'lat:' + e[0] + 'lon' + e[1] + 'altitude: ' + e[2]
      });*/
      recorrido.push({id:i, lat: e[0], lng:e[1] });


    });

    //console.log(recorrido)


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
      strokeColor: '#000',
      strokeOpacity: 1.0,
      strokeWeight: 3
    });

    recorridoPolyline.setMap(map);
    recorridoPolylineBK1.setMap(map)
    recorridoPolylineBK2.setMap(map)

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

    recorridoPolylineBK2.addListener('mouseout', function(ev){
      outRecorrido();
    });
    recorridoPolylineBK1.addListener('mouseout', function(ev){
      outRecorrido();
    });
    recorridoPolyline.addListener('mouseout', function(ev){
      outRecorrido();
    });



    var chartString = "step,Altitude\n";
    $.each( route.trackPoints, function(i,e){
      //if( typeof e[2] == 'undefined' ) {
        chartString += i + "," + e[2] + "\n";
      //}
      //else {
        //chartString += i + ",0\n";
      //}

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

        hoverRecorrido( seleccionado )

    }).mouseleave(function(e) {
        var seleccionada = grafico.getSelection();
        outRecorrido();


    });


  },



  findPoint: function(lat, lng) {

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
      hoverRecorrido( lessDistanceElementId );
    }
  }


  hoverRecorrido: function( id ) {
    //grafico.setSelection(id);
    grafico.setSelection(id) ;

    if( trackCircle ) {

      trackCircle.setMap(null);
    }
    var scale = Math.pow(2, map.getZoom());



    trackCircle = new google.maps.Circle({
        center: {lat: route.trackPoints[id][0] , lng: route.trackPoints[id][1]},
        radius: 850000/scale,
        strokeWeight:0,
        fillColor: "#0000FF",
        fillOpacity: 1,
        map: map
    });
  }

  outRecorrido: function( ) {

    if( trackCircle ) {
      trackCircle.setMap(null);
    }

  }






});
