var geozzy = geozzy || {};

$(document).ready( function() {

  initMap( geozzy.rextMapDirections.directions );

} );

function initMap( directions ) {
  var directionsService = new google.maps.DirectionsService;
  var directionsDisplay = new google.maps.DirectionsRenderer;
  var map = new google.maps.Map( document.getElementById( 'map' ) );
  directionsDisplay.setMap( map );
  directionsDisplay.setPanel( document.getElementById( 'routeDirectionList' ) );

  calculateAndDisplayRoute( directionsService, directionsDisplay, directions );
  google.maps.event.addListenerOnce( map, 'tilesloaded', function() {
    setTimeout( function() { window.print(); }, 1000 );
  } );
}

function calculateAndDisplayRoute( directionsService, directionsDisplay, directions ) {
  directionsService.route( {
    origin: directions.origin,
    destination: directions.destination,
    travelMode: google.maps.TravelMode[ directions.travelMode ]
  }, function( response, status ) {
    if( status === 'OK' ) {
      directionsDisplay.setDirections( response );
    }
    else {
      window.alert( 'Directions request failed due to ' + status );
    }
  } );
}
