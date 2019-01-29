


$(document).ready( function() {

  if( typeof cogumelo.publicConf.rextMapConf == 'undefined') {
    cogumelo.publicConf.rextMapConf = {};
    cogumelo.publicConf.rextMapConf.mapLoadOnDemandRtypes = [];
  }

  if( geozzy.rExtMapInstance == false || typeof( geozzy.rExtMapInstance ) == 'undefined' ) {
    geozzy.rExtMapInstance = new geozzy.rExtMapController( geozzy.rExtMapOptions );

    if(
      typeof resourceRtypeIdName != 'undefined' &&
      $.inArray( resourceRtypeIdName, cogumelo.publicConf.rextMapConf.mapLoadOnDemandRtypes ) != -1
    ){
      geozzy.rExtMapInstance.renderInitButton();
    }
    else {
      geozzy.rExtMapInstance.initialize();
    }
  }
});
