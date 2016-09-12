
  $(document).ready(function(){

    //console.log('LOOOOOOOOOOOOOOOOOOOOOOOOOOOOOO' , geozzy.collection.CategorytermCollection );
    var poisTypes = new geozzy.collection.CategorytermCollection();
    poisTypes.setUrlByIdName('rextPoiType');


    $.when( poisTypes.fetch() ).done(function() {
      poisResourceExplorer( poisTypes );
    });

  });



  /*
  * Create the POIS resource explorer
  */
  function poisResourceExplorer( poisTypes ) {

    /* EXPLORER MAIN CLASS DECLARATION */
    var ex = new geozzy.explorer({
      explorerId:'pois',
      explorerSectionName:'Puntos de interese',
      debug:false,
      aditionalParameters:geozzy.rExtPOIOptions,
      resetLocalStorage: true
    });





    /* EXPLORER DISPLAY DECLARATION  (SET CUSTOM ICON TOO)  */
    var explorerMapa = new geozzy.explorerComponents.mapView({
        map: geozzy.rExtMapInstance.resourceMap,
        clusterize:false,
        chooseMarkerIcon: function( markerData ) {
          //return cogumelo.publicConf.media+'/module/rextPoiCollection/img/poi.png';
          return {
            url: cogumelo.publicConf.media+'/module/rextPoiCollection/img/poi.png',
            // This marker is 20 pixels wide by 36 pixels high.
            size: new google.maps.Size(15, 15),
            // The origin for this image is (0, 0).
            origin: new google.maps.Point(0, 0),
            // The anchor for this image is the base of the flagpole at (0, 36).
            anchor: new google.maps.Point(6, 6)
          }
        }
    });



    /* ADD DISPLAY TO EXPLORER */
    ex.addDisplay( explorerMapa );

    var miniInfoWindow ='<div class="poiInfoWindow">'+
                          '<div class="poiImg">'+
                            '<img class="img-responsive" src="'+cogumelo.publicConf.mediaHost+'cgmlImg/<%-image%>/listEvent/<%-image%>.jpg" />'+
                          '</div>'+
                          '<div class="poiInfo">'+
                            '<div class="poiTitle"><p><%-title%></p></div>'+
                            '<div class="poiDescription"><%-description%></div>'+
                          '</div>'
                        '</div>';

    var infowindow = new geozzy.explorerComponents.mapInfoBubbleView({tpl:miniInfoWindow});
    ex.addDisplay( infowindow );
  /*  var infowindow = new geozzy.explorerComponents.mapInfoView({
      tpl: miniInfoWindow
    }

    );
    ex.addDisplay( infowindow );
  */
    /* EXEC EXPLORER */
    ex.exec();
  }
