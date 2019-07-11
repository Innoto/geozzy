var geozzy = geozzy || {};


geozzy.rExtMapWidgetFormPositionView  = Backbone.View.extend({
  parent: false,
  autocomplete:false,
  editing: false,
  events: {
  },

  initialize: function() {
    var that = this;
  },

  render: function() {
    var that = this;

    that.parent.$el.find( '.resourceLocationFrame .locationDialog' ).append('<div class="locationFormMap" style="display:none;"></div>');
    that.parent.$el.find( '.resourceLocationFrame .locationDialog .locationFormMap' ).html(that.parent.$el.find(' .locationForm').html());

    that.parent.$el.find('.locationForm').remove();

    that.latInput = that.parent.$el.find(".lat input");
    that.lonInput = that.parent.$el.find(".lon input");
    that.zoomInput = that.parent.$el.find(".zoom input");
    that.addressInput = that.parent.$el.find(".searchBox .address");


    that.parent.addToolBarbutton({
        id: 'position',
        icon: '<div>Edit marker</div>',
        iconHover:'<div>Edit marker hover</div>',
        iconSelected: '<div>Editing marker</div>',
        onclick: function() {
          that.startEdit();
        }
    });


    var my_marker = {
      url:  cogumelo.publicConf.admin.adminMap.marker,
      // This marker is 20 pixels wide by 36 pixels high.
      size: new google.maps.Size(30, 36),
      // The origin for this image is (0, 0).
      origin: new google.maps.Point(0, 0),
      // The anchor for this image is the base of the flagpole at (0, 36).
      anchor: new google.maps.Point(13, 36)
    };


    var markerData = {
      title: 'Resource location',
      icon: my_marker
      //draggable: true,
    };

    if( that.latInput.val() != '' ) {
      markerData.position = new google.maps.LatLng( that.latInput.val(), that.lonInput.val() );
      markerData.map = that.parent.mapObject;
      that.parent.mapObject.setCenter(new google.maps.LatLng( that.latInput.val(), that.lonInput.val() ));
    }

    that.resourceMarker = new google.maps.Marker(markerData);


    // Draggend event
    google.maps.event.addListener( that.resourceMarker,'dragend',function(e) {
      that.lonInput.val( that.resourceMarker.position.lng() );
      that.latInput.val( that.resourceMarker.position.lat() );
    });

    // Click map event
    google.maps.event.addListener(that.parent.mapObject, 'click', function(e) {

      if( that.editing == true) {
        that.resourceMarker.setPosition( e.latLng );
        that.resourceMarker.setMap( that.parent.mapObject );
        that.latInput.val( that.resourceMarker.position.lat() );
        that.lonInput.val( that.resourceMarker.position.lng() );

        that.zoomInput.val( that.parent.mapObject.getZoom() );
      }

    });

    // map zoom changed
    google.maps.event.addListener(that.parent.mapObject, 'zoom_changed', function(e) {
      that.zoomInput.val( that.parent.mapObject.getZoom() );
    });

    if( that.latInput.val() !== '') {
      that.resourceMarker.setMap( that.parent.mapObject);
    }
    that.initAddressAutocompletion();
  },


  initAddressAutocompletion: function() {
    var that = this;

    if( that.addressInput.length > 0 ) {
      // Create the autocomplete object, restricting the search to geographical
      // location types.

      that.autocomplete = new google.maps.places.Autocomplete(
        ( that.addressInput[0] ),
        {types: ['geocode']}
      );

      // When the user selects an address from the dropdown, populate the address
      // fields in the form.
      that.autocomplete.addListener('place_changed', that.fillInAddress);

      that.addressInput.focus( function() {
        that.geolocate();
      });
    }
  },

  geolocate: function() {
    var that = this;

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
  },

  fillInAddress: function() {
    var that = this;
    // Get the place details from the autocomplete object.
    var place = that.autocomplete.getPlace();
    console.log(place.geometry.location.lat(), place.geometry.location.lng() );

    var pos = new google.maps.LatLng(
      place.geometry.location.lat(),
      place.geometry.location.lng()
    );

    that.parent.mapObject.setCenter( pos );
    that.resourceMarker.setPosition( pos );
    that.resourceMarker.setMap( that.parent.mapObject );
    that.latInput.val( that.resourceMarker.position.lat() );
    that.lonInput.val( that.resourceMarker.position.lng() );
    that.parent.mapObject.setZoom(11);
  },


  startEdit: function() {
    var that = this;
    //that.lockToolbar('position');
    that.editing = true;
    that.resourceMarker.setOptions({
      draggable: true
    });

    that.parent.$el.find( '.resourceLocationFrame .locationButtons' ).show();
    that.parent.$el.find( '.resourceLocationFrame .locationDialog .locationFormMap' ).show();

    return false;
  },

  endEditCancel: function() {
    var that = this;
    that.editing = false;
    that.resourceMarker.setOptions({
      draggable: false
    });

    that.parent.$el.find( '.resourceLocationFrame .locationDialog .locationFormMap' ).hide();
    that.parent.$el.find( '.resourceLocationFrame .locationButtons' ).hide();
    return false;
  },

  endEditSuccess: function() {
    var that = this;
    that.editing = false;
    that.resourceMarker.setOptions({
      draggable: false
    });


    that.parent.$el.find( '.resourceLocationFrame .locationDialog .locationFormMap' ).hide();
    that.parent.$el.find( '.resourceLocationFrame .locationButtons' ).hide();
    
    return false;
  }

});
