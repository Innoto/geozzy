$(document).ready( function(){

  if( typeof geozzy.rExtMapInstance.resourceMap != 'undefined') {

    new google.maps.KmlLayer(rextKMLFile, {
      preserveViewport: true,
      map: geozzy.rExtMapInstance.resourceMap
    });

    console.log('kml layer', rextKMLFile);

    //alert(rextKMLFile)
  }



});