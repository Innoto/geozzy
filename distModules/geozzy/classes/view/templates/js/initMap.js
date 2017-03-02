console.log('Cargamos initMap.js');

$(document).ready( function() {
  // Porto 160810
  if( typeof initializeMap === 'function' ) {
    console.log('Lanzamos initializeMaps( formId )');
    initializeMaps( formId );
  }
  else {
    console.log('NO lanzamos initializeMaps( formId )');
  }
});
