function initialize() {

    var map_options = {
            zoom: 6,
            center: new google.maps.LatLng(0, 0),
            mapTypeId: google.maps.MapTypeId.ROADMAP
            };
    map = new google.maps.Map( document.getElementById( 'map-canvas' ), map_options );
}

function create_polygon(coordinates) {
    var icon = {
        //path: google.maps.SymbolPath.CIRCLE,
        path: "M -1 -1 L 1 -1 L 1 1 L -1 1 z",
        strokeColor: "#555555",
        strokeOpacity: 0,
        fillColor: "#555555",
        fillOpacity: 1,
        scale: 5
    };

     var polygon = new google.maps.Polygon({
        map: map,
        paths: coordinates,
        strokeColor: "#555555",
        strokeOpacity: 0.8,
        strokeWeight: 2,
        fillColor: "#555555",
        fillOpacity: 0.5,
        zIndex: -1
    });

    var marker_options = {
        map: map,
        icon: icon,
        flat: true,
        draggable: true,
        raiseOnDrag: false
    };



    for (var i=0; i<coordinates.length; i++){
        marker_options.position = coordinates[i];
        var point = new google.maps.Marker(marker_options);

        google.maps.event.addListener(point, "drag", update_polygon_closure(polygon, i));
    }

    function update_polygon_closure(polygon, i){
        return function(event){
          polygon.getPath().setAt(i, event.latLng);
        }
    }

    function borra_ultimo() {
    	polygon.getPath().pop();
    }

    function novo( ev ) {

    	polygon.getPath().push(ev.latLng);
      console.log( polygon.getPath().getArray().length );

      marker_options.position = ev.latLng;
      var point = new google.maps.Marker(marker_options);
      google.maps.event.addListener(point, "drag", update_polygon_closure(
      	polygon,
      	polygon.getPath().getArray().length -1
      ));
    }

    google.maps.event.addListener(map, "rightclick",function(){ borra_ultimo();});
    google.maps.event.addListener(map, "click", function(ev){ novo(ev);});
};

initialize();


var corners = [[2, 0.63]
              ];
var coordinates = [];

for (var i=0; i<corners.length; i++){
    var position = new google.maps.LatLng(corners[i][0], corners[i][1]);

    coordinates.push(position);
    }

create_polygon(coordinates);
