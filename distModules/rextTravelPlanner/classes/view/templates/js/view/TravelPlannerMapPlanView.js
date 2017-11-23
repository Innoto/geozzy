var geozzy = geozzy || {};
if(!geozzy.travelPlannerComponents) geozzy.travelPlannerComponents={};

geozzy.travelPlannerComponents.TravelPlannerMapPlanView = Backbone.View.extend({
  el: "#travelPlannerSec",
  //datesTemplate : false,
  //modalTemplate : false,
  parentTp : false,
  map : false,
  currentDay : 0,
  markers : [],
  selectedMarkers : [],
  planDays: 0,

  events: {
    'click .travelPlannerMapPlan  .filterDay-previous': 'previousDay',
    'click .travelPlannerMapPlan .filterDay-next': 'nextDay'
  },

  initialize: function( parentTp ) {
    var that = this;
    that.delegateEvents();
    that.parentTp = parentTp;

    var checkin =  that.parentTp.momentDate( that.parentTp.tpData.get('checkin') );
    var checkout = that.parentTp.momentDate( that.parentTp.tpData.get('checkout') );
    that.planDays = 1 + checkout.diff( checkin, 'days');
  },
  render: function() {
    var that = this;
    var checkin =  that.parentTp.momentDate( that.parentTp.tpData.get('checkin') );
    var checkout = that.parentTp.momentDate( that.parentTp.tpData.get('checkout') );
    that.planDays = 1 + checkout.diff( checkin, 'days');

    if( parseInt(that.currentDay + 1 ) >= that.planDays ){
      that.currentDay = 0;
    }

    that.showDay(that.currentDay);
  },
  setInitMap: function(){
    var that = this;


    eval("var estilosMapa = "+cogumelo.publicConf.rextMapConf.styles+";");


    that.mapOptions = {
      center: {lat:parseFloat(cogumelo.publicConf.rextMapConf.defaultLat),lng:parseFloat(cogumelo.publicConf.rextMapConf.defaultLng) }, //{ lat: 43.1, lng: -7.36 },
      mapTypeControl: false,
      fullscreenControl: false,
      mapTypeId: cogumelo.publicConf.rextMapConf.mapTypeId,
      zoom: cogumelo.publicConf.rextMapConf.defaultZoom,
      styles : estilosMapa
    };

    if(that.map === false){
      that.map = new google.maps.Map( that.$('.travelPlannerMapPlan .map').get( 0 ), that.mapOptions);
      google.maps.event.addListener( that.map, 'idle' ,function(e) {
        that.centerMap();
      });
    }
    else {
      google.maps.event.trigger(that.map, 'resize');
    }
  },
  showDay: function(daySelected){
    var that = this;

    that.currentDay = daySelected;
    that.setInitMap();
    that.printDataOnMap();
    that.changeDay();

  },
  previousDay: function(e){
    var that = this;
    if(that.currentDay !== 0){
      that.showDay(parseInt(that.currentDay)-1);
    }
  },
  nextDay: function(e){
    var that = this;
    that.showDay(parseInt(that.currentDay)+1);
  },
  changeDay: function(){
    var that = this;
    that.$('.travelPlannerMapPlan .filterDay-current span.number').html(parseInt(that.currentDay)+1);
    that.$('.travelPlannerMapPlan .filterDay-previous').removeClass('notVisible');
    that.$('.travelPlannerMapPlan .filterDay-next').removeClass('notVisible');
    if(that.currentDay == 0 ){
      that.$('.travelPlannerMapPlan .filterDay-previous').addClass('notVisible');
    }
    if(parseInt(that.currentDay)+1 === that.planDays){
      that.$('.travelPlannerMapPlan .filterDay-next').addClass('notVisible');
    }
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
    that.selectedMarkers = [];

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
      that.selectedMarkers.push(gMarker);
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
    //var bounds = new google.maps.LatLngBounds();
    /*
    for (var i = 0; i < that.selectedMarkers.length; i++) {
     bounds.extend(that.selectedMarkers[i].getPosition());
    }
    that.map.fitBounds(bounds);
    */
  }
});
