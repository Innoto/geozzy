var geozzy = geozzy || {};
if(!geozzy.travelPlannerComponents) geozzy.travelPlannerComponents={};

geozzy.travelPlannerComponents.TravelPlannerMapView = Backbone.View.extend({
  el: "#travelPlannerSec",
  //datesTemplate : false,
  //modalTemplate : false,
  parentTp : false,
  map : false,
  currentDay : 0,
  markers : [],

  events: {
    'click .travelPlannerMap .closeMap': 'closeMap',
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
  setInitMap: function(){
    var that = this;
    that.mapOptions = {
      center: { lat: 43.1, lng: -7.36 },
      mapTypeControl: false,
      fullscreenControl: false,
      zoom: 7//,
      /*styles : mapTheme*/
    };
    if(that.map === false){
      that.map = new google.maps.Map( that.$('.travelPlannerMap .map').get( 0 ), that.mapOptions);
    }
  },
  closeMap: function(e){
    var that = this;
    that.$('.travelPlanner').removeClass('mapOn');
  },
  showDay: function(daySelected){
    var that = this;

    that.currentDay = daySelected;
    console.log('View MAP', that.currentDay);

    that.setInitMap();
    that.printDataOnMap();
    that.changeDay();
    that.$('.travelPlanner').addClass('mapOn');

  },
  previousDay: function(e){
    var that = this;
    if(that.currentDay !== 0){

      that.showDay(parseInt(that.currentDay)-1);
      that.changeDay();
    }
  },
  nextDay: function(e){
    var that = this;

    that.showDay(parseInt(that.currentDay)+1);
    that.changeDay();
  },
  changeDay: function(){
    var that = this;
    that.$('.travelPlannerMap .filterDay-current span.number').html(parseInt(that.currentDay)+1);
  },
  printDataOnMap: function(){
    var that = this;

    var resSelected = [];
    var resSelectedInDay = [];

    $(that.parentTp.tpData.get('list')).each( function(iday,day) {
      $(day).each( function(i,item){
        if( that.currentDay  == iday ){
          resSelectedInDay.push(item.id);
        }
        resSelected.push(item.id);
      });
    });

    resSelected = $.unique(resSelected);

    var resourcesToList = [];
    resourcesToList = that.parentTp.resources;
    resourcesToList = new geozzy.collection.ResourceCollection( resourcesToList.filterById(resSelected) );

    that.removeMarkers();
    that.markers = [];

    $.each( resourcesToList.toJSON(), function(i ,item){
      var pos = $.inArray(String(item.id), resSelectedInDay);
      if(pos === -1){
        that.addMarkerOnMap(item);
      }
      else{
        that.addMarkerOnMap(item,'selected', pos);
      }
    });
    that.centerMap();
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
    that.markers.push(gMarker);
  },

  removeMarkers: function(){
    var that = this;
    $.each( that.markers, function(i ,marker){
      marker.setMap( null );
    });
  },

  centerMap: function(){
    var that = this;
    var bounds = new google.maps.LatLngBounds();
    for (var i = 0; i < that.markers.length; i++) {
     bounds.extend(that.markers[i].getPosition());
    }
    that.map.fitBounds(bounds);
  }
});
