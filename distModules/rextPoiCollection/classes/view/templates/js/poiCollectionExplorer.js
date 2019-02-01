
  $(document).ready(function(){

    //cogumelo.log('LOOOOOOOOOOOOOOOOOOOOOOOOOOOOOO' , geozzy.collection.CategorytermCollection );


    var categorias = {};
    var multifetchStr = 'categorias.rtypePoi.fetch()';

    categorias.rtypePoi = new geozzy.collection.CategorytermCollection();
    categorias.rtypePoi.setUrlByIdName('rextPoiType');


    if( typeof cogumelo.publicConf.rtypeTaxonomygroup != 'undefined') {

      $( Object.keys(cogumelo.publicConf.rtypeTaxonomygroup) ).each( function(i, key){
        eval( 'var e = cogumelo.publicConf.rtypeTaxonomygroup.'+ key);

        eval( 'categorias.' + key + ' = new geozzy.collection.CategorytermCollection();'  );
        eval( 'categorias.' + key + '.setUrlByIdName("'+e+'");'  );
        multifetchStr += ', '+ 'categorias.' + key + '.fetch()';
      });
    }


    eval('$.when( '+ multifetchStr+' ).done(function() {  poisResourceExplorer( categorias ); });');

  });



  /*
  * Create the POIS resource explorer
  */
  function poisResourceExplorer( categorias ) {

    /* EXPLORER MAIN CLASS DECLARATION */
    var ex = new geozzy.explorer({
      explorerId:'pois',
      explorerSectionName:'Puntos de interese',
      debug:false,
      aditionalParameters:geozzy.rExtPOIOptions,
      resetLocalStorage: true
    });


    ex.bindEvent('mapResourceClick', function(o) {
      //alert(o.id);
      //cogumelo.log(ex.resourceMinimalList.get(o.id).toJSON());


      if( ex.resourceMinimalList.get(o.id).get('isNormalResource') == 1 ) {
        var win = window.open( '/'+cogumelo.publicConf.C_LANG+'/resource/'+o.id, '_blank');
        win.focus();
      }

      //cogumelo.log(o.id, ex.resourceMinimalList.get(o.id) );
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


          var resTerms = markerData.get('terms');
          var resRtype = markerData.get('rType');

          if(  categorias.hasOwnProperty(resRtype) ) {

            eval('var taxgroup = categorias.' + resRtype );
            taxgroup.each( function(e){
              if( $.inArray(e.get('id'), resTerms ) > -1 ) {
                if( jQuery.isNumeric( e.get('icon') )  ){
                  retMarker.url = cogumelo.publicConf.mediaHost+'cgmlImg/'+e.get('icon')+'/resourcePoisCollection/marker.png';
                  retMarker.size =  new google.maps.Size(20, 20);
                  return false;
                }
              }
            });

          }



          return retMarker;
        }
    });

    if( geozzy.rExtPOIOptions.showPanorama != false) {
      var panoramaView = new geozzy.explorerComponents.panoramaView({
        panoramaImage: geozzy.rExtPOIOptions.panoramaImg
      });
      ex.addDisplay( panoramaView );
    }


    /* ADD DISPLAY TO EXPLORER */

    ex.addDisplay( explorerMapa );

    var miniInfoWindow ='<div class="poiInfoWindow" <% if( isNormalResource == 1 ) { %> onclick="window.open(\'/'+ cogumelo.publicConf.C_LANG +'/resource/<%- id %>\')"<%}%> >'+
                          '<div class="poiImg">'+
                            '<img class="img-fluid" src="'+cogumelo.publicConf.mediaHost+'cgmlImg/<%-image%>/squareCut/<%-image%>.jpg" />'+
                          '</div>'+
                          '<div class="poiInfo">'+
                            '<div class="poiTitle"><p><%-title%></p></div>'+
                            '<div class="poiDescription"><%=description%></div>'+
                            //'<% if( isNormalResource == 1 ) { %> <a target="_blank" href="/resource/<%-id%>" ><button class="btn btn-primary accessButton">' + __('Discover') + '</button> </a><% }%>'

                          '</div>'+
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
    geozzy.rExtMapInstance.onLoad(function(){
      ex.exec();
    });

  }
