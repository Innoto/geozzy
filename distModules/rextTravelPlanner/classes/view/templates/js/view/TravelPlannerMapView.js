var geozzy = geozzy || {};
if(!geozzy.travelPlannerComponents) geozzy.travelPlannerComponents={};

geozzy.travelPlannerComponents.TravelPlannerMapView = Backbone.View.extend({
  el: "#travelPlannerSec",
  //datesTemplate : false,
  //modalTemplate : false,
  parentTp : false,
  map : false,
  markers : [],
  selectedMarkers : [],
  planDays: 0,
/*
  markerIcon: {
    path: google.maps.SymbolPath.CIRCLE,
    fillOpacity: 1,
    fillColor: '#5DAAE1',
    strokeOpacity: 1,
    strokeWeight: 1,
    strokeColor: '#fff',
    scale: 6
  },

  markerIconSelected:{
    path: google.maps.SymbolPath.CIRCLE,
    fillOpacity: 1,
    fillColor: '#E16A4E',
    strokeOpacity: 1,
    strokeWeight: 1,
    strokeColor: '#fff',
    scale: 6
  },
*/
  events: {
    'click .travelPlannerMap .filterDay-previous': 'previousDay',
    'click .travelPlannerMap .filterDay-next': 'nextDay'
  },

  initialize: function( parentTp ) {
    var that = this;
    that.delegateEvents();
    that.parentTp = parentTp;
  },
  render: function() {
    var that = this;
  },
  setInitMap: function( allResources ){
    var that = this;

    that.allResourcesCollection = allResources;

    if(that.map === false){

      eval("var estilosMapa = "+cogumelo.publicConf.rextMapConf.styles+";");
      that.mapOptions = {
        center: {lat:parseFloat(cogumelo.publicConf.rextMapConf.defaultLat),lng:parseFloat(cogumelo.publicConf.rextMapConf.defaultLng) }, //{ lat: 43.1, lng: -7.36 },
        mapTypeControl: false,
        fullscreenControl: false,
        mapTypeId: cogumelo.publicConf.rextMapConf.mapTypeId,
        zoom: cogumelo.publicConf.rextMapConf.defaultZoom,
        styles : estilosMapa
      };

      that.map = new google.maps.Map( that.$('.travelPlannerMap .map').get( 0 ), that.mapOptions);
      that.setMarkers();
    }
    else {
      google.maps.event.trigger(that.map, 'resize');
    }
  },
  setMarkers: function() {
    var that = this;

    that.parentTp.resources.each( function(e,i) {

      var markerLoc = e.get('loc');
      //console.log( e.get('loc') );
      if( markerLoc!= null && typeof markerLoc.lat != 'undefined' && typeof markerLoc.lng != 'undefined' ) {

        e.set('marker',
          new google.maps.Marker({
            position: new google.maps.LatLng( markerLoc.lat, markerLoc.lng ),
            icon: that.markerIcon
          })
        );

      }


    });
  },

  showMarkers: function( visibleList ) {
    var that = this;

    // lista de seleccionados
    var selectedList = [];

    $.each( that.parentTp.tpData.get('list'), function(i,e) {
      if(e!= 'false') {
        $.each(e, function(i2,e2) {
          if(e2!= 'false') {
            if( jQuery.inArray( parseInt(e2.id), selectedList )  == -1 ){
              selectedList.push(parseInt(e2.id));
            }
          }
        });
      }
    });

    that.parentTp.resources.each( function(e,i) {
      var currentMarker = e.get('marker');
      if( currentMarker ) {



        if( jQuery.inArray( e.get('id'), selectedList )  != -1 ) {
          currentMarker.setIcon( that.getICon(e.get('id'), true) );
          currentMarker.setMap(that.map);
        }
        else
        if( jQuery.inArray( e.get('id'), visibleList )  != -1 ) {  // in list
          currentMarker.setIcon( that.getICon(e.get('id'), false) );
          currentMarker.setMap(that.map);
        }
        else{ // not in list
          currentMarker.setMap(null);
        }
      }
    });

  },

  getICon: function( resourceId, selected ) {
    var that = this;
    var retObj = false;
    var iconUrl = false;
    //console.log(that.parentTp.resources.get(resourceId).get('categoryIds') );
    //currentCategory

    if(selected) {
      console.log('seleccionado');
      var iconProfile = cogumelo.publicConf.mod_geozzy_travelPlanner.markerImgProfileSelected;
    }
    else {
      var iconProfile = cogumelo.publicConf.mod_geozzy_travelPlanner.markerImgProfile;
    }

    $.each(that.parentTp.categories, function(i,e) {
      e.each( function(e2,i2) {
        //console.log( e2.id );

        if( jQuery.inArray( e2.id , that.parentTp.resources.get(resourceId).get('categoryIds')  )  != -1 ) {
          iconUrl = cogumelo.publicConf.mediaHost+'cgmlImg/'+e2.get('icon')+'-a'+e2.get('iconAKey')+'/'+iconProfile+'/marker.png';
          return;
        }


      });
    });


    if(iconUrl) {
      retObj = {
        url: iconUrl,
        // This marker is 20 pixels wide by 36 pixels high.
        size: new google.maps.Size(24, 24),
        // The origin for this image is (0, 0).
        origin: new google.maps.Point(0, 0),
        // The anchor for this image is the base of the flagpole at (0, 36).
        anchor: new google.maps.Point(12, 12)
      };
    }
    else
    {
      retObj = {
        url: cogumelo.publicConf.rextMapConf.defaultMarker,
      };
    }

    return retObj;

  },


  markerBounce: function(id) {
    var that = this;



    // set as selected
    var selectedMarker = that.parentTp.resources.get(id).get('marker');
    if( selectedMarker ) {
      //selectedMarker.setMap(that.map)
      selectedMarker.setOptions({
        title: 'selected'
      });
    }
  },
  markerBounceEnd: function(id) {
    var that = this;

    // set as selected
    var selectedMarker = that.parentTp.resources.get(id).get('marker');
    if( selectedMarker ) {
      //selectedMarker.setMap(null)
      selectedMarker.setOptions({
        title: 'selected'
      });
    }
  }


});
