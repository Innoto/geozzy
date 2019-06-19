  $(document).ready( function(){

    geozzy.rExtMapInstance.onLoad( function() {
      var coords = [];

      if(geozzy.rExtMapPolygonCoords) {

        $.each( geozzy.rExtMapPolygonCoords , function(i,e) {

          coords.push({lat:e[0], lng:e[1]});
        });

        var polygonAttributes = { //= new google.maps.Polygon({
           map: geozzy.rExtMapInstance.resourceMap,
           paths: coords,
           strokeColor: "#555555",
           strokeOpacity: 0.2,
           strokeWeight: 2,
           fillColor: "#555555",
           fillOpacity: 0.2,
           zIndex: -1,
           clickable:false
        };//);


        new google.maps.Polygon(polygonAttributes);

      }
    });
  });
