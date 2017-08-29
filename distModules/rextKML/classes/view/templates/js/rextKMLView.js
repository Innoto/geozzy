$(document).ready( function(){

  if( typeof geozzy.rExtMapInstance.resourceMap != 'undefined') {
alert(rextKMLFile);
    var kmlLayer = new google.maps.KmlLayer(rextKMLFile, {
      suppressInfoWindows: true,
      preserveViewport: false,
      map: geozzy.rExtMapInstance.resourceMap
    });

  }



});
