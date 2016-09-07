
  $(document).ready(function(){

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
          return cogumelo.publicConf.media+'/module/rextPoiCollection/img/poi.png';
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
                        '</div>'

    var infowindow = new geozzy.explorerComponents.mapInfoView({
      tpl: miniInfoWindow
    }

    );
    ex.addDisplay( infowindow );

    /* EXEC EXPLORER */
    ex.exec();
  });
