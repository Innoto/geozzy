var geozzy = geozzy || {};
if(!geozzy.travelPlannerComponents) geozzy.travelPlannerComponents={};

geozzy.travelPlannerComponents.TravelPlannerMapPlanView = Backbone.View.extend({
  el: "#travelPlannerSec",
  //datesTemplate : false,
  //modalTemplate : false,
  parentTp : false,
  map : false,
  directionsDisplay: false,
  snappedCoordinates : [],
  tramoExtraArray: [],
  placeIdArray : [],
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
      styles : estilosMapa,
      gestureHandling: 'greedy'
    };

    if(that.map === false){
      that.map = new google.maps.Map( that.$('.travelPlannerMapPlan .map').get( 0 ), that.mapOptions);
      google.maps.event.addListener( that.map, 'idle' ,function(e) {

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
    that.renderDirectionsDisplay();
  },
  previousDay: function(e){
    var that = this;
    if(that.currentDay !== 0){
      $('html,body').animate({scrollTop: $('#plannerDay-'+(parseInt(that.currentDay)-1)).offset().top},'slow');
      that.showDay(parseInt(that.currentDay)-1);
    }
  },
  nextDay: function(e){
    var that = this;
    $('html,body').animate({scrollTop: $('#plannerDay-'+(parseInt(that.currentDay)+1)).offset().top},'slow');
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
    var resSelectedInDayData = [];

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
    $.each( resSelectedInDay, function(i ,item){
      resSelectedInDayData.push(resourcesToList.get(item).toJSON());
    });
    that.calcRoute(resSelectedInDayData);
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

  renderDirectionsDisplay: function(){
    var that = this;
    if(that.directionsDisplay !== false){
      that.clearRoute();
    }
    that.directionsDisplay = new google.maps.DirectionsRenderer({ suppressMarkers: true });
    that.directionsDisplay.setMap(that.map);
  },
  calcRoute: function( dataPoints ){
    var that = this;
    var firstLoc = false;
    var lastLoc = false
    var waypointsLoc = [];
    var startLocation = null;
    var endLocation = null;
    var waypts = null;
    var directionsService = new google.maps.DirectionsService();

    if(dataPoints.length > 1){
      //Generamos array con las coords
      $.each(dataPoints, function( i, el ) {
        waypointsLoc.push({ location: el.loc });
      });
      //Extraemos el inicio y fin de ruta
      firstLoc = waypointsLoc.shift();
      lastLoc = waypointsLoc.pop();

      var request = {
        region: 'es',
        origin: firstLoc.location,
        destination: lastLoc.location,
        waypoints: waypointsLoc,
        /*optimizeWaypoints: true,*/
        travelMode: google.maps.DirectionsTravelMode.DRIVING
      };

      directionsService.route(request, function(response, status) {
        if (status == google.maps.DirectionsStatus.OK) {
          that.directionsDisplay.setDirections(response);
          that.tramoExtraArray = [];
          that.tramoExtraArray.push(that.tramoExtra( response.request.origin.location, response.routes[0].legs[0].start_location ));
          $.each(response.routes[0].legs, function( i, leg ) {
            if( (i+1) !== response.routes[0].legs.length ){
              that.tramoExtraArray.push(that.tramoExtra( response.request.waypoints[i].location.location, leg.end_location ));
            }else{
              //Ultimo
              that.tramoExtraArray.push(that.tramoExtra( response.request.destination.location, leg.end_location ));
            }
          });
        }
        else {
          console.log("directions status "+status);
        }
      });
    }
  },

  tramoExtra: function tramoExtra( init, end ) {
    //console.log( 'tramoExtra:', init, end, reves );
    var that = this;
    var tramo = false;
    var diferencia = Math.abs( init.lat() - end.lat()) + Math.abs( init.lng() - end.lng());
    var fromTo = false;

    if ( !isNaN(diferencia) && diferencia>0.0001 ) {
      fromTo = [ init, end ];

      tramo = new google.maps.Polyline({
        path: fromTo,
        strokeOpacity: 0,
        icons: [{
          icon: { path:'M 0,0 L 1,0 L 1,1 L 0,1 z',  fillColor:'#589CF5', strokeColor:'#589CF5', strokeOpacity:0.7, scale:3 },
          //icon:{ path:'M 1,0 0,2 -1,0 z', fillColor:'#66F', strokeColor:'#66F', strokeOpacity:0.7, scale:2 },
          offset: '0',
          repeat: '10px'
        }],
        map: that.map
      });
    }

    return tramo;
  },

  clearRoute: function clearRoute() {
    var that = this;
    // console.log( 'clearRoute:' );
    // borra ruta del mapa
    if( that.directionsDisplay ) {
      that.directionsDisplay.setDirections( {routes: []} );
    }
    if( that.tramoExtraArray && that.tramoExtraArray.length > 0 ) {
      $.each( that.tramoExtraArray, function( i, tramo ) {
        if(tramo){
          tramo.setMap( null );
        }
      });
      that.tramoExtraArray = [];
    }
  }
});
