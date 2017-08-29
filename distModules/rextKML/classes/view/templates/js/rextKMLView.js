$(document).ready( function(){

  if( typeof geozzy.rExtMapInstance.resourceMap != 'undefined') {

    var kmlLayer = new google.maps.KmlLayer(rextKMLFile, {
      suppressInfoWindows: true,
      preserveViewport: false,
      map: geozzy.rExtMapInstance.resourceMap
    });

  }



});
