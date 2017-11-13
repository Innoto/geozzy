


$(document).ready( function() {
  if( geozzy.rExtMapInstance == false || typeof( geozzy.rExtMapInstance ) == 'undefined' ) {
    geozzy.rExtMapInstance = new geozzy.rExtMapController( geozzy.rExtMapOptions );
    geozzy.rExtMapInstance.initialize();
  }
});
