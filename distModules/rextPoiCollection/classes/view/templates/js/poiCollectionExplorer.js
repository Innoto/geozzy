
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


    ex.bindEvent('mapResourceClick', function(o) {
      //alert(o.id)
      //console.log(ex.resourceMinimalList.get(o.id).toJSON())


      if( ex.resourceMinimalList.get(o.id).get('isNormalResource') == 1 ) {
        var win = window.open( '/'+cogumelo.publicConf.C_LANG+'/resource/'+o.id, '_blank');
        win.focus();
      }

      //console.log(o.id, ex.resourceMinimalList.get(o.id) )
    });



    /* EXPLORER DISPLAY DECLARATION  (SET CUSTOM ICON TOO)  */
    var explorerMapa = new geozzy.explorerComponents.mapView({
        map: geozzy.rExtMapInstance.resourceMap,
        clusterize:false,
        chooseMarkerIcon: function( markerData ) {
          //return cogumelo.publicConf.media+'/module/rextPoiCollection/img/poi.png';


          var retMarker = {
            url: cogumelo.publicConf.media+'/module/rextPoiCollection/img/chapaPOIS.png',
            // This marker is 20 pixels wide by 36 pixels high.
            size: new google.maps.Size(20, 20),
            // The origin for this image is (0, 0).
            origin: new google.maps.Point(0, 0),
            // The anchor for this image is the base of the flagpole at (0, 36).
            anchor: new google.maps.Point(10, 10)
          };



          poisTypes.each( function(e){
            console.log(cogumelo.publicConf.mediaHost+'cgmlImg/'+e.get('icon')+'/resourcePoisCollection/marker.png')
            if( $.inArray(e.get('id'), markerData.get('terms')) > -1 ) {
              if( jQuery.isNumeric( e.get('icon') )  ){
                retMarker.url = cogumelo.publicConf.mediaHost+'cgmlImg/'+e.get('icon')+'/resourcePoisCollection/marker.png';
                retMarker.size =  new google.maps.Size(20, 20);
                return false;
              }
            }
          });

          return retMarker;
        }
    });



    /* ADD DISPLAY TO EXPLORER */
    ex.addDisplay( explorerMapa );

    var miniInfoWindow ='<div class="poiInfoWindow" <% if( isNormalResource == 1 ) { %> onclick="window.open(\'/'+ cogumelo.publicConf.C_LANG +'/resource/<%- id %>\')"<%}%> >'+
                          '<div class="poiImg">'+
                            '<img class="img-responsive" src="'+cogumelo.publicConf.mediaHost+'cgmlImg/<%-image%>/squareCut/<%-image%>.jpg" />'+
                          '</div>'+
                          '<div class="poiInfo">'+
                            '<div class="poiTitle"><p><%-title%></p></div>'+
                            '<div class="poiDescription"><%-description%></div>'+
                            //'<% if( isNormalResource == 1 ) { %> <a target="_blank" href="/resource/<%-id%>" ><button class="btn btn-primary accessButton">' + __('Discover') + '</button> </a><% }%>'

                          '</div>'
                        '</div>';


    var infowindow = new geozzy.explorerComponents.mapInfoBubbleView({
      tpl:miniInfoWindow,
      width: 350,
      max_height:170,
      marker_distance: [20,20]
    });
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
