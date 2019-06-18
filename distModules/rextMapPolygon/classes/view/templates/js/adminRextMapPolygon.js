$(document).ready( function(){

  onLoadMaps( function() {
    var adminRextMapPolygonInstance = new adminRextMapPolygon();

    adminRextMapPolygonInstance.initialize();
  });

});


var adminRextMapPolygon = function() {
  var that = this;

  that.editing = false;
  that.cornerMarkers = false;
  that.polygon = false;
  that.polygonAttributes = { //= new google.maps.Polygon({
     map: resourceFormMaps[0].resourceMap,
     //paths: coordinates,
     strokeColor: "#555555",
     strokeOpacity: 0.2,
     strokeWeight: 2,
     fillColor: "#555555",
     fillOpacity: 0.2,
     zIndex: -1,
     clickable:false
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

    $('.rExtMapPolygon .btnClearPolygon').on('click', function(){
      that.clearAllCorners();
    });


    google.maps.event.addListener( resourceFormMaps[0].resourceMap, "rightclick",function(){
      that.removeCorner();
      that.updateInputFromCorners();
    });
    google.maps.event.addListener( resourceFormMaps[0].resourceMap, "click", function(ev){
      that.addCorner(ev.latLng);
      that.updateInputFromCorners();
    });
  };

  that.startEdit = function() {
    $('.rExtMapPolygon .btnEditPolygon').hide();
    $('.rExtMapPolygon .desc').show();
    $('.rExtMapPolygon .btnEndEditPolygon').show();
    $('.rExtMapPolygon .btnClearPolygon').show();

    resourceFormMaps[0].blockMarker = true;
    that.editing = true;
    if( that.cornerMarkers != false) {
      $.each(that.cornerMarkers, function(i,marker) {
        marker.setMap(resourceFormMaps[0].resourceMap);
      });
      that.polygon.set( 'fillOpacity' , 0.8 );
      that.polygon.set( 'strokeOpacity' , 0.8 );
    }

  };

  that.stopEdit = function() {
    $('.rExtMapPolygon .btnEditPolygon').show();
    $('.rExtMapPolygon .btnEndEditPolygon').hide();
    $('.rExtMapPolygon .btnClearPolygon').hide();
    $('.rExtMapPolygon .desc').hide();
    resourceFormMaps[0].blockMarker = false;
    that.editing = false;
    if( that.cornerMarkers != false) {
      $.each(that.cornerMarkers, function(i,marker) {
        marker.setMap(null);
      });
      that.polygon.set( 'fillOpacity' , that.polygonAttributes.fillOpacity );
      that.polygon.set( 'strokeOpacity' , that.polygonAttributes.strokeOpacity );
    }
  };


  that.addCorner = function( latLng, force ) {

    if( that.editing == true || force == true){

      if( that.polygon != false ) {
        that.polygon.getPath().push(latLng);
      }
      else {
        that.polygonAttributes.paths = [latLng];
        that.polygon = new google.maps.Polygon(that.polygonAttributes);
        that.cornerMarkers = [];
      }

      var ind = that.polygon.getPath().getArray().length -1;

      that.marker_options.position = latLng;
      var point = new google.maps.Marker(that.marker_options);
      if( that.editing ){
        point.setMap(resourceFormMaps[0].resourceMap);
      }
      google.maps.event.addListener(point, "drag", function(ev) {
        that.polygon.getPath().setAt( ind , ev.latLng);
        that.updateInputFromCorners();
      });
      that.cornerMarkers.push(point);

    }
  };


  that.removeCorner = function( ) {
    if( that.editing == true ){
      var deletedMarker = that.cornerMarkers.pop();
      deletedMarker.setMap(null);
      that.polygon.getPath().pop();
    }
  };

  that.clearAllCorners = function() {
    while( that.polygon.getPath().getArray().length > 0 ) {
      that.removeCorner();
    }
  };

  that.updateInputFromCorners = function() {

    $('.rExtMapPolygon input.cgmMForm-field-rExtMapPolygon_polygonGeometry').val(  that.coordsToArrayStr( that.polygon.getPath().getArray()) );
  };


  that.updateCornersFromInput = function() {

    var polygonCornersText = $('.rExtMapPolygon input.cgmMForm-field-rExtMapPolygon_polygonGeometry').val().replace(/ /g,''); // without spaces
    var polygonCornersArray;

    if( polygonCornersText != '') {
      eval('polygonCornersArray = new Array(' + polygonCornersText + ');' );
      $.each( that.arrayToCoords( polygonCornersArray ), function(i,e){
        that.addCorner(e, true);
      });
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
      cordsStr += coma + '[' + e.lat().toFixed(7) + ',' + e.lng().toFixed(7) + ']';
      coma = ',';
    });
    return cordsStr;
  };

};
