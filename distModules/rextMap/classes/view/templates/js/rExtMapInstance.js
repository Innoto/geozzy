


$(document).ready( function() {

  if( typeof cogumelo.publicConf.rextMapConf == 'undefined') {
    cogumelo.publicConf.rextMapConf = {};
    cogumelo.publicConf.rextMapConf.mapLoadOnDemandRtypes = [];
  }

  if( geozzy.rExtMapInstance == false || typeof( geozzy.rExtMapInstance ) == 'undefined' ) {

    geozzy.rExtMapInstance = new geozzy.rExtMapController( geozzy.rExtMapOptions );

    if(
      typeof geozzy.rExtMapOptions.resourceRtypeIdName != 'undefined' &&
      $.inArray( geozzy.rExtMapOptions.resourceRtypeIdName, cogumelo.publicConf.rextMapConf.mapLoadOnDemandRtypes ) != -1
    ){
      geozzy.rExtMapInstance.renderInitButton();
    }
    else {
      geozzy.rExtMapInstance.initialize();
    }

    geozzy.rExtMapInstance.url = window.location;
  }
});
