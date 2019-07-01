var geozzy = geozzy || {};


geozzy.rExtMapWidgetFormPositionView  = Backbone.View.extend({
  parent: false,

  events: {
  },

  initialize: function() {
    var that = this;
  },

  render: function() {
    var that = this;


    that.latInput = that.parent.$el.find(".lat input");
    that.lonInput = that.parent.$el.find(".lon input");
    that.zoomInput = that.parent.$el.find(".zoom input");
    that.addressInput = that.parent.$el.find(".address");

    that.parent.addToolBarbutton( {
        icon: '',
        iconHover:'',
        iconSelected: '',
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
      icon: my_marker,
      draggable: true,
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
      that.resourceMarker.setPosition( e.latLng );
      that.resourceMarker.setMap( that.parent.mapObject );
      that.latInput.val( that.resourceMarker.position.lat() );
      that.lonInput.val( that.resourceMarker.position.lng() );

      that.zoomInput.val( that.parent.mapObject.getZoom() );
    });

    // map zoom changed
    google.maps.event.addListener(that.parent.mapObject, 'zoom_changed', function(e) {
      that.zoomInput.val( that.parent.mapObject.getZoom() );
    });

    if( that.latInput.val() !== '') {
      that.resourceMarker.setMap( that.parent.mapObject);
    }

  },



  startEdit: function() {
    var that = this;
    return false;
  },

  endEdit: function() {
    var that = this;
    return false;
  }

});
