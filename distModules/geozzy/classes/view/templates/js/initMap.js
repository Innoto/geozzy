console.log('Cargamos initMap.js');

$(document).ready( function() {
  // Porto 160810
  if( typeof initializeMap === 'function' ) {
    console.log('Lanzamos initializeMap( formId )');
    initializeMap( formId );
  }
  else {
    console.log('NO lanzamos initializeMap( formId )');
  }
});

