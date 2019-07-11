var geozzy = geozzy || {};

/*
  HTML example to add a form map
*/
geozzy.rExtMapWidgetForm  = Backbone.View.extend({

  mapConteiner: '.mapContainer',
  dialogConteiner: '.mapContainer',
  mapReady: false,
  mapObject: false,
  toolBarObject: false,
  components: [],
  events: {
    "click .buttonEditLocation": "changeViewMode",
    "click  .resourceLocationColumn .locationButtons  .endEditCancel": "endEditCancelComponents",
    "click .resourceLocationColumn .locationButtons  .endEditSuccess": "endEditSuccessComponents",

  },

  initialize: function() {
    var that = this;
    that.initializeMap();

    that.changeViewMode(); //borrar esto
  },

  initializeMap: function(){
    var that = this;

    that.$el.append(
      '<div class="resourceLocationFrame">'+
      ' <div class="resourceLocationMap"></div>' +
      ' <div class="resourceLocationColumn">'+
      '  <div class="searchBox"><input class="address"><div class="automaticBtn btn btn-primary pull-right">Automatic Location</div></div>' +
      '  <div class="locationToolBox"></div>' +
      '  <div class="locationButtons" style="display:none;">'+
      '     <div class="editIcon"> </div> '  +
      '     <div class="endEditCancel btn btn-warning pull-right">Cancel</div> ' +
      '     <div class="endEditSuccess btn btn-primary pull-right">Ok</div> </div>' +
      '  <div class="locationDialog">asdfdfas</div>' +
      ' </div>' +
      '</div>'
    );

    var latInit = 0;
    var lonInit = 0;
    var zoomInit = 3;

    if(typeof cogumelo.publicConf.admin.adminMap === 'undefined') {
      cogumelo.log('cogumelo.publicConf.admin.adminMap is not defined in conf');
    }
    else {
      latInit = Number(cogumelo.publicConf.admin.adminMap.defaultLat);
      lonInit = Number(cogumelo.publicConf.admin.adminMap.defaultLon);
      zoomInit = Number(cogumelo.publicConf.admin.adminMap.defaultZoom);
    }

    // gmaps init
    var mapOptions = {
      center: { lat: latInit, lng: lonInit },
      zoom: zoomInit,
      scrollwheel: false,
      mapTypeControl: false,
      fullscreenControl: false,
      streetViewControl: false
    };
    that.mapObject = new google.maps.Map( that.$el.find('.resourceLocationMap')[0], mapOptions);
    google.maps.event.addListenerOnce( that.mapObject ,'idle',function(e) {
      that.mapReady = true;
    });
  },

  renderDialog: function(content) {
    var that = this;
    that.$el.find( that.dialogConteiner );
  },

  addToolBarbutton: function( button ) {
    var that = this;

    that.$el.find('.locationToolBox').append(
      '<div class="button" idToolButton="'+button.id+'">' +
      ' <div class="toolboxIcon icon">'+button.icon+'</div>'+
      '</div>'
    );

    var btDiv = that.$el.find('.locationToolBox').find( ".button[idToolButton='" + button.id + "']" );
    btDiv.on( 'click', function(ev) {
      that.$el.find('.resourceLocationColumn  .locationToolBox').hide();
      that.$el.find( '.resourceLocationColumn .locationButtons .editIcon' ).html(button.icon);
      button.onclick();
    });

  },



  changeViewMode: function() {
    var that =this;
    if( that.$el.closest('.card').hasClass('locationFullScreen') ) {
      that.endEditCancelComponents();
    }

    that.$el.closest('.card').toggleClass( 'locationFullScreen' );


  },

  addComponent: function( component ) {
    var that = this;

    component.parent = that;
    that.components.push( component );

    if( that.mapReady == true ){
      component.render();
    }
    else {
      google.maps.event.addListenerOnce( that.mapObject ,'idle',function(e) {
        component.render();
      });
    }

  },

  endEditCancelComponents: function() {
    var that = this;
    that.$el.find('.resourceLocationColumn  .locationToolBox').show();
    $.each(that.components, function(i,e){
      e.endEditCancel();
    });
  },

  endEditSuccessComponents: function() {
    var that = this;
    that.$el.find('.resourceLocationColumn  .locationToolBox').show();
    $.each(that.components, function(i,e){
      e.endEditSuccess();
    });
  }

/*
  trigerEvent: function( eventName ) {
    var that = this;

    $.each( components, function(i,e){
      eval('e.'+eventName+'()');
    });
  }
*/

});




/*

geozzy.rExtMapWidgetForm = function( segmentDIV ) {
  var that = this;

  that.segmentDIV = segmentDIV;

  that.latInput = that.segmentDIV.find(".lat input");
  that.lonInput = that.segmentDIV.find(".lon input");
  that.defaultZoom = that.segmentDIV.find(".zoom input");
  that.mapContainer = that.segmentDIV.find(".mapContainer");
  that.addressInput = that.segmentDIV.find(".address");
  that.resourceMap = false;
  that.resourceMarker = false;

  that.initializeMap = function( ){
    // Location Map
    if(  that.segmentDIV.find(".lat input").length &&  that.segmentDIV.find(".lon input").length  ) {


      if( that.mapContainer.length && that.mapContainer.children('.resourceLocationMap').length < 1 ) {
        that.mapContainer.append('<div class="resourceLocationMap"></div>');

        var latValue = 0;
        var lonValue = 0;
        var zoomInit = 3;

        if(typeof cogumelo.publicConf.admin.adminMap === 'undefined') {
          console.log('cogumelo.publicConf.admin.adminMap is not defined in conf');
        }
        else {
          var latInit = Number(cogumelo.publicConf.admin.adminMap.defaultLat);
          var lonInit = Number(cogumelo.publicConf.admin.adminMap.defaultLon);
          zoomInit = Number(cogumelo.publicConf.admin.adminMap.defaultZoom);
          var defaultMarker = cogumelo.publicConf.admin.adminMap.marker;

        }


        if( that.latInput.val() !== '' && that.latInput.val() !== '') {
          latValue = parseFloat( that.latInput.val() );
          lonValue = parseFloat( that.lonInput.val() );
          latInit = latValue;
          lonInit = lonValue;
        }

        if( that.defaultZoom.length > 0 &&  that.defaultZoom.val() != '') {
          zoomInit = parseInt( that.defaultZoom.val() );
        }


        // gmaps init
        var mapOptions = {
          center: { lat: latInit, lng: lonInit },
          zoom: zoomInit,
          scrollwheel: false
        };
        that.resourceMap = new google.maps.Map( that.segmentDIV.find('.resourceLocationMap')[0], mapOptions);

        // add marker

        var my_marker = {
          url: defaultMarker,
          // This marker is 20 pixels wide by 36 pixels high.
          size: new google.maps.Size(30, 36),
          // The origin for this image is (0, 0).
          origin: new google.maps.Point(0, 0),
          // The anchor for this image is the base of the flagpole at (0, 36).
          anchor: new google.maps.Point(13, 36)
        };

        that.resourceMarker = new google.maps.Marker({
          position: new google.maps.LatLng( latValue, lonValue ),
          title: 'Resource location',
          icon: my_marker,
          draggable: true
        });

        // Draggend event
        google.maps.event.addListener( that.resourceMarker,'dragend',function(e) {
          that.lonInput.val( that.resourceMarker.position.lng() );
          that.latInput.val( that.resourceMarker.position.lat() );
        });

        // Click map event
        google.maps.event.addListener(that.resourceMap, 'click', function(e) {
          that.resourceMarker.setPosition( e.latLng );
          that.resourceMarker.setMap( that.resourceMap );
          that.latInput.val( that.resourceMarker.position.lat() );
          that.lonInput.val( that.resourceMarker.position.lng() );

          that.defaultZoom.val( that.resourceMap.getZoom() );
        });

        // map zoom changed
        google.maps.event.addListener(that.resourceMap, 'zoom_changed', function(e) {
          that.defaultZoom.val( that.resourceMap.getZoom() );
        });

        if( that.latInput.val() !== '') {
          that.resourceMarker.setMap( that.resourceMap);
        }
      }
    }
  };


  that.placeSearch = false;
  that.autocomplete = false;
  that.componentForm = {
    street_number: 'short_name',
    route: 'long_name',
    locality: 'long_name',
    administrative_area_level_1: 'short_name',
    country: 'long_name',
    postal_code: 'short_name'
  };


  that.fillInAddress = function() {
    // Get the place details from the autocomplete object.
    var place = that.autocomplete.getPlace();
    console.log(place.geometry.location.lat(), place.geometry.location.lng() );

    var pos = new google.maps.LatLng(
      place.geometry.location.lat(),
      place.geometry.location.lng()
    )

    that.resourceMap.setCenter( pos );


    that.resourceMarker.setPosition( pos );
    that.resourceMarker.setMap( that.resourceMap );
    that.latInput.val( that.resourceMarker.position.lat() );
    that.lonInput.val( that.resourceMarker.position.lng() );

    that.resourceMap.setZoom(11);

  };

  // Bias the autocomplete object to the user's geographical location,
  // as supplied by the browser's 'navigator.geolocation' object.
  that.geolocate = function() {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(function(position) {
        var geolocation = {
          lat: position.coords.latitude,
          lng: position.coords.longitude
        };
        var circle = new google.maps.Circle({
          center: geolocation,
          radius: position.coords.accuracy
        });
        that.autocomplete.setBounds(circle.getBounds());
      });
    }
  };

  that.initAddressAutocompletion = function() {
    if( that.addressInput.length > 0 ) {
      // Create the autocomplete object, restricting the search to geographical
      // location types.

      that.autocomplete = new google.maps.places.Autocomplete(
          ( that.addressInput[0] ),
          {types: ['geocode']});

      // When the user selects an address from the dropdown, populate the address
      // fields in the form.
      that.autocomplete.addListener('place_changed', that.fillInAddress);

      that.addressInput.focus( function() {
        that.geolocate();
      });
    }
  };


  that.initializeMap();
  that.initAddressAutocompletion();
}
*/
