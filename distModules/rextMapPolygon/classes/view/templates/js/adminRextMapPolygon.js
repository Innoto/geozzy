$(document).ready( function(){

  onLoadMaps( function() {
    var adminRextMapPolygonInstance = new adminRextMapPolygon();

    adminRextMapPolygonInstance.initialize();
  });

});


var adminRextMapPolygon = function() {
  var that = this;

  that.editing = false;
  //that.polygonString = '[1,10],[10,10]';
  that.corners = false;  // ex ... [[2, 0.63], [lat, lng]];
  that.polygon = false;
  that.polygonAttributes = { //= new google.maps.Polygon({
     map: resourceFormMaps[0].resourceMap,
     //paths: coordinates,
     strokeColor: "#555555",
     strokeOpacity: 0.8,
     strokeWeight: 2,
     fillColor: "#555555",
     fillOpacity: 0.5,
     zIndex: -1
  };//);

  that.icon = {
      //path: google.maps.SymbolPath.CIRCLE,
      path: "M -1 -1 L 1 -1 L 1 1 L -1 1 z",
      strokeColor: "#555555",
      strokeOpacity: 0,
      fillColor: "#555555",
      fillOpacity: 1,
      scale: 5
  };



  that.marker_options = {
      //map: map,
      icon: that.icon,
      flat: true,
      draggable: true,
      raiseOnDrag: false
  };


  that.initialize = function() {
    that.updateCornersFromInput();

    // events
    $('.rExtMapPolygon .btnEditPolygon').on('click', function(){
      that.startEdit();
    });

    $('.rExtMapPolygon .btnEndEditPolygon').on('click', function(){
      that.stopEdit();
    });

    google.maps.event.addListener( resourceFormMaps[0].resourceMap, "rightclick",function(){ that.removeCorner();});
    google.maps.event.addListener( resourceFormMaps[0].resourceMap, "click", function(ev){ that.addCorner(ev.latLng);});
  };

  that.startEdit = function() {
    $('.rExtMapPolygon .btnEditPolygon').hide();
    $('.rExtMapPolygon .desc').show();
    $('.rExtMapPolygon .btnEndEditPolygon').show();
    resourceFormMaps[0].blockMarker = true;
    that.editing = true;
  };

  that.stopEdit = function() {
    $('.rExtMapPolygon .btnEditPolygon').show();
    $('.rExtMapPolygon .btnEndEditPolygon').hide();
    $('.rExtMapPolygon .desc').hide();
    resourceFormMaps[0].blockMarker = false;
    that.editing = false;
  };


  that.addCorner = function( latLng ) {
alert('')
    if( that.editing == true ){

      if( that.polygon != false ) {
        that.polygon.getPath().push(latLng);
      }
      else {
        that.polygonAttributes.paths = [latLng];
        that.polygon = new google.maps.Polygon(that.polygonAttributes);

      }



      that.marker_options.position = latLng;
      var point = new google.maps.Marker(that.marker_options);
      google.maps.event.addListener(point, "drag", update_polygon_closure(
        that.polygon,
        that.polygon.getPath().getArray().length -1
      ));
    }
  };


  that.removeCorner = function( ) {
    if( that.editing == true ){
      that.polygon.getPath().pop();
    }
  };


  that.updateInputFromCorners = function() {
    // meter este array polygon.getPath().getArray() en input $('.rExtMapPolygon .polygonCorners').val();
    // pasa a formato array con coordsToArrayStr
  };


  that.updateCornersFromInput = function() {

    var polygonCornersText = $('.rExtMapPolygon .polygonCorners').val().replace(/ /g,''); // without spaces
    var polygonCornersArray;

    if( polygonCornersText != '') {
      eval('polygonCornersArray = [' + polygonCornersText + '];' );
      if( polygonCornersArray.isArray() ) {
        $.each( that.arrayToCoords( polygonCornersArray ), function(i,e){
          that.addCorner(e);
        });
      }
    }
  };


  that.arrayToCoords = function( arr ) {
    var cordsArray = [];
    $.each( arr, function(i,e) {
      cordsArray.push( new google.maps.LatLng(e[0], e[1]) );
    });
    return cordsArray;
  };

  that.coordsToArrayStr = function( cords ) {
    var cordsStr = '';
    var coma = '';
    $.each( cords, function(i,e) {
      cordsStr += coma + '[' + e.lat() + ',' + e.lng + ']';
      coma = ',';
    });
    return cordsStr;
  };

};



/*
function initialize() {

    var map_options = {
            zoom: 6,
            center: new google.maps.LatLng(0, 0),
            mapTypeId: google.maps.MapTypeId.ROADMAP
            };
    map = new google.maps.Map( document.getElementById( 'map-canvas' ), map_options );
}*/
/*
function create_polygon(coordinates) {

    for (var i=0; i<coordinates.length; i++){
        that.marker_options.position = coordinates[i];
        var point = new google.maps.Marker(that.marker_options);

        google.maps.event.addListener(point, "drag", update_polygon_closure(that.polygon, i));
    }

    function update_polygon_closure(that.polygon, i){
        return function(event){
          that.polygon.getPath().setAt(i, event.latLng);
        }
    }

    function borra_ultimo() {
    	that.polygon.getPath().pop();
    }

    function novo( ev ) {

    	that.polygon.getPath().push(ev.latLng);
      console.log( polygon.getPath().getArray().length );

      that.marker_options.position = ev.latLng;
      var point = new google.maps.Marker(that.marker_options);
      google.maps.event.addListener(point, "drag", update_polygon_closure(
      	that.polygon,
      	that.polygon.getPath().getArray().length -1
      ));
    }

    google.maps.event.addListener(map, "rightclick",function(){ borra_ultimo();});
    google.maps.event.addListener(map, "click", function(ev){ novo(ev);});
};

//initialize();



var coordinates = [];

for (var i=0; i<corners.length; i++){
    var position = new google.maps.LatLng(corners[i][0], corners[i][1]);

    coordinates.push(position);
    }
*/
//create_polygon(coordinates);
