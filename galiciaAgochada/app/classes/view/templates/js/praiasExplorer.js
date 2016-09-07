


  $(document).ready(function(){
    explorador = new praiasExplorer();

    explorador.setInitialData( function() {
      explorador.setExplorer();
      explorador.setDisplays();
      explorador.setFilters();
      explorador.exec();
      explorador.layoutDistributeSize();
    });

    $(window).bind("load resize", function() {
      explorador.layoutDistributeSize();
    });

    if( document.cookie.replace(' ','').indexOf('geozzy-modal-welc-praias=1')==-1 ) {
      $('.modalWelcome').modal();
      $('.modalWelcome').on('hidden.bs.modal', function (e) {
        /*
        var fecha = new Date();
        fecha.setTime(fecha.getTime()+(365*24*60*60*1000));
        document.cookie = "geozzy-modal-welc-praias=1; expires="+fecha.toGMTString()+"; path=/";
        */
        document.cookie = "geozzy-modal-welc-praias=1; expires=; path=/";
      });
    }

  });









  /****************************
    paisaxesExplorer
   ****************************/
  praiasExplorer = function() {
    var that = this;


    that.explorerclass = '.praiasExplorer';
    that.mapOptions = false;
    that.resourceMap  = false;
    that.fussFTLayer = false;

    that.infowindow = false;
    that.listaMini = false;
    that.mapa = false;




    /**
      setInitialData. Preset objects and get values for the filters
     */
    that.setInitialData = function( doneFunction ){
      that.mapOptions = {
        center: { lat: 43.1, lng: -7.36 },
        mapTypeControl: false,
        zoom: 8,
        styles : mapTheme
      };
      that.resourceMap = new google.maps.Map( $( that.explorerclass+' .explorerMap').get( 0 ), that.mapOptions);

      google.maps.event.addListenerOnce( that.resourceMap , 'idle', function(){
        explorador.layoutDistributeSize();
      });

      mapControlUtils = new mapControlsUtils();
      mapControlUtils.changeMapControls(that.resourceMap);

      // Multiple data fetch
      doneFunction();
    }




    /**
      setExplorer. instance the explorer object
     */
    that.setExplorer = function() {

      that.explorer = new geozzy.explorer({
        partialLoadSuccess: function(){ that.layoutDistributeSize() },        
        debug: false,
        explorerId:'praias',
        explorerSectionName:'Praias de ensono',
        resourceAccess: function(id) {
          $(".explorerContainer.explorer-loading").show();
          $(".explorerContainer.explorer-container-du").load(
            '/'+cogumelo.publicConf.C_LANG+'/resource/'+id,
            { pf: 'blk' },
            function() {
              $(".explorerContainer.explorer-loading").hide();
              $(".explorerContainer.explorer-container-du").show();
            }
          );

        },
        resourceQuit: function() {
          $(".explorerContainer.explorer-container-du").hide();
          $(".explorerContainer.explorer-container-du").html('');
        }

      });

    }





    /**
      setDisplays. set explorer display objects
     */
    that.setDisplays = function() {

      that.infowindow = new geozzy.explorerComponents.mapInfoView();
      that.listaMini = new geozzy.explorerComponents.activeListTinyView({ el:$('.explorer-container-gallery')});
      that.listaRecomendados =  new geozzy.explorerComponents.reccommendedListView();
      that.mapa = new geozzy.explorerComponents.mapView({
          map: that.resourceMap,
          clusterize:false,
          chooseMarkerIcon: function( markerData ) {
            var iconUrl = false;
            var retObj = false;

            retObj = {
              url: cogumelo.publicConf.media+'/img/chapas/chapaPraias.png',
              // This marker is 20 pixels wide by 36 pixels high.
              size: new google.maps.Size(24, 24),
              // The origin for this image is (0, 0).
              origin: new google.maps.Point(0, 0),
              // The anchor for this image is the base of the flagpole at (0, 36).
              anchor: new google.maps.Point(12, 12)
            }


            return retObj;
          }
      });


      that.explorer.addDisplay( that.listaMini );
      that.explorer.addDisplay( that.listaRecomendados );
      that.explorer.addDisplay( that.mapa );
      that.explorer.addDisplay( that.infowindow );

    }






    /**
      setFilters. set explorer filter objects
     */
    that.setFilters = function() {

      name = __('Praias de Ensono');

      $( that.explorerclass+' .explorer-container-filter').html(
        '<div class="titleBar">'+
          '<div class="container">'+
            '<div class="row">'+
              '<div class="col-md-12 explorerTitle" >'+
                '<img class="iconTitleBar img-responsive" alt="'+name+'" '+
                  ' src="'+cogumelo.publicConf.media+'/img/praiasIcon.png"></img>'+
                '<h1>'+name+'</h1>'+
              '</div>'+
            '</div>'+
          '</div>'+
        '</div>'
      );
    }

    /**
      exec. execs the explorer
     */
    that.exec = function(){
      that.explorer.exec();
    }

    /**
      layoutDistributeSize. util method
     */
    that.layoutDistributeSize = function(){
      var hExplorerLayout = $('.praiasExplorer').height();
      var hExplorerFilters = $('.praiasExplorer .explorer-container-filter').height();
      var hExplorerGallery = $('.praiasExplorer .explorer-container-gallery').height();
      var hHeader = 60;
      var hExplorerMap = hExplorerLayout - (hExplorerGallery + hExplorerFilters + hHeader);

      $('.praiasExplorer .explorer-container-map').height( hExplorerMap );
    }
  }
