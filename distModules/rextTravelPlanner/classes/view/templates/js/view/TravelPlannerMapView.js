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
          currentMarker.setIcon( that.markerIconSelected);
          currentMarker.setMap(that.map);
        }
        else
        if( jQuery.inArray( e.get('id'), visibleList )  != -1 ) {  // in list
          currentMarker.setIcon( that.markerIcon);
          currentMarker.setMap(that.map);
        }
        else{ // not in list
          currentMarker.setMap(null);
        }
      }
    });

  }



});
