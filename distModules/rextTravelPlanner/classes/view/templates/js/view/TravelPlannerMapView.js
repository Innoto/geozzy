var geozzy = geozzy || {};
if(!geozzy.travelPlannerComponents) geozzy.travelPlannerComponents={};

geozzy.travelPlannerComponents.TravelPlannerMapView = Backbone.View.extend({
  el: "#travelPlannerSec",
  //datesTemplate : false,
  //modalTemplate : false,
  parentTp : false,
  map : false,
  markers : [],

  events: {

  },

  initialize: function( parentTp ) {
    var that = this;
    that.delegateEvents();
    that.parentTp = parentTp;

  },
  render: function() {
    var that = this;
  },
  setInitMap: function(){
    var that = this;

    that.mapOptions = {
      center: { lat: 43.1, lng: -7.36 },
      mapTypeControl: false,
      fullscreenControl: false,
      zoom: 7//,
      /*styles : mapTheme*/
    };
    that.map = new google.maps.Map( that.$('.travelPlannerMap .map').get( 0 ), that.mapOptions);
  },
  showDay: function(daySelected){
    var that = this;

    console.log('View MAP', daySelected);

    that.$('.travelPlanner').addClass('mapOn');
    that.setInitMap();
    that.printDataOnMap(daySelected);
  },
  printDataOnMap: function(daySelected){
    var that = this;

    var resSelected = [];
    var resSelectedInDay = [];

    $(that.parentTp.tpData.get('list')).each( function(iday,day) {
      $(day).each( function(i,item){
        if( daySelected  == iday ){
          resSelectedInDay.push(item.id);
        }
        resSelected.push(item.id);
      });
    });

    resSelected = $.unique(resSelected);

    var resourcesToList = [];
    resourcesToList = that.parentTp.resources;
    resourcesToList = new geozzy.collection.ResourceCollection( resourcesToList.filterById(resSelected) );

    that.marker = [];

    $.each( resourcesToList.toJSON(), function(i ,item){
      var pos = $.inArray(String(item.id), resSelectedInDay);
      if(pos === -1){
        that.addMarkerOnMap(item);
      }
      else{
        that.addMarkerOnMap(item,'selected', pos);
      }
    });

  },
  addMarkerOnMap: function(item, type, label){
    var that = this;

    if(type === "selected"){
      var Icono = {
        path: google.maps.SymbolPath.CIRCLE,
        fillOpacity: 1,
        fillColor: '#5AB780',
        strokeOpacity: 1,
        strokeWeight: 2,
        strokeColor: '#fff',
        scale: 10
      };
      var gMarker = new google.maps.Marker({
        map: that.map,
        position: new google.maps.LatLng( item.loc.lat, item.loc.lng ),
        icon: Icono,
        label: { color: '#fff', fontSize: '12px', fontWeight: '600',
          text: String(label + 1 ) }
      });
    }else{
      var Icono = {
        path: google.maps.SymbolPath.CIRCLE,
        fillOpacity: 1,
        fillColor: '#E16A4E',
        strokeOpacity: 1,
        strokeWeight: 1,
        strokeColor: '#fff',
        scale: 6
      };
      var gMarker = new google.maps.Marker({
        map: that.map,
        position: new google.maps.LatLng( item.loc.lat, item.loc.lng ),
        icon: Icono,
      });
    }
    that.marker.push(gMarker);
  }
});
