$(document).ready( function(){

  if( typeof geozzy.rExtMapInstance.resourceMap != 'undefined' || geozzy.rExtMapInstance.resourceMap) {

    new google.maps.KmlLayer(rextKMLFile, {
      preserveViewport: true,
      map: geozzy.rExtMapInstance.resourceMap
    });

    cogumelo.log('kml layer', rextKMLFile);

    //alert(rextKMLFile)
  }



});
