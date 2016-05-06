var geozzy = geozzy || {};




geozzy.rExtMapController = function( opts ) {


  var that = this;

  that.resourceMap = false;
  that.resourceMarker = false;


  that.options = {
    lat: false,
    lng: false,
    zoom: 8,
    wrapper: false

  };


  $.extend(true, that.options, opts);

  that.initialize = function() {

    var $mapContainer = $( that.options.wrapper );
    console.log( that.options.wrapper  )
    if( $mapContainer.length === 1 ) {
      // console.log( 'prepareMap - OK: ATOPADO O WRAPPER DO MAPA!!!' );
      // gmaps init
      that.resourceMapOptions = {
        center: { lat: that.options.lat, lng: that.options.lng },
        zoom: that.options.zoom,
        scrollwheel: false
      };

      that.resourceMap = new google.maps.Map( $mapContainer.get(0), that.resourceMapOptions );


      // add marker
      that.resourceMarker = new google.maps.Marker({
        map: that.resourceMap,
        position: new google.maps.LatLng( that.options.lat, that.options.lng ),
        title: 'Resource location',
        icon: {
          url: cogumelo.publicConf.media+'/module/admin/img/geozzy_marker.png', // that marker is 20 pixels wide by 36 pixels high.
          size: new google.maps.Size(30, 36), // The origin for that image is (0, 0).
          origin: new google.maps.Point(0, 0), // The anchor for that image is the base of the flagpole at (0, 36).
          anchor: new google.maps.Point(13, 36)
        }
      });



    } // if( $mapContainer.length )
    else {
      console.log( 'prepareMap - ERROR: NON ENCONTRO O WRAPPER DO MAPA!!!' );
    }
  };


};
