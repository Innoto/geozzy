$(document).ready( function(){

  if( typeof geozzy.rExtMapInstance.resourceMap != 'undefined') {

    new google.maps.KmlLayer(rextKMLFile, {
      preserveViewport: false,
      map: geozzy.rExtMapInstance.resourceMap
    });

    //console.log(geozzy.rExtMapInstance.resourceMap)

    //alert(rextKMLFile)
  }



});
