var geozzy = geozzy || {};
if(!geozzy.travelPlannerComponents) geozzy.travelPlannerComponents={};

geozzy.travelPlannerComponents.TravelPlannerMapPlanView = Backbone.View.extend({
  el: "#travelPlannerSec",
  //datesTemplate : false,
  //modalTemplate : false,
  parentTp : false,
  map : false,
  directionsDisplay: false,
  directionsService: false,
  tramoExtraArray: [],
  directionsServiceRequest: [],
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
    if( parseInt(that.currentDay)+1 >= that.planDays ){
      that.currentDay = 0;
    }
    that.showDay(that.currentDay);

    if(cogumelo.publicConf.mod_detectMobile_isMobile){
      var userAgent = navigator.userAgent.toLowerCase();
      var isAndroidChrome = /chrome/.test(userAgent) && /android/.test(userAgent);
      var isIOSChrome = /crios/.test(userAgent);

      if (isAndroidChrome || isIOSChrome) {

        var elemFix = $('.fixVhChromeMobile');
        if($('.fixVhChromeMobile').length > 0){
          $.each( elemFix, function(i, e){
            $(e).innerHeight( $(this).innerHeight() );
          });
        }
      }
    }
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
    that.printMarkersOnMap();
    that.startRouteOnMap();
    that.changeDay();
  },
  showOptimizeDay: function(daySelected){
    var that = this;
    that.currentDay = daySelected;
    that.setInitMap();
    that.startRouteOnMap(true);
    that.changeDay();
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
  printMarkersOnMap: function(){
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
  },
  addMarkerOnMap: function(item, type, label){
    var that = this;

    var markSelectedColor = (cogumelo.publicConf.mod_geozzy_travelPlanner.colorMarkerPlanSelected) ? cogumelo.publicConf.mod_geozzy_travelPlanner.colorMarkerPlanSelected : '#E26553';
    var markColor = (cogumelo.publicConf.mod_geozzy_travelPlanner.colorMarkerPlan) ? cogumelo.publicConf.mod_geozzy_travelPlanner.colorMarkerPlan : '#E26553';

    if(type === "selected"){
      var Icono = {
        path: google.maps.SymbolPath.CIRCLE,
        fillOpacity: 1,
        fillColor: markSelectedColor,
        strokeOpacity: 1,
        strokeWeight: 2,
        strokeColor: '#fff',
        scale: 13
      };
      var gMarker = new google.maps.Marker({
        map: that.map,
        position: new google.maps.LatLng( item.loc.lat, item.loc.lng ),
        icon: Icono,
        label: { color: '#fff', fontSize: '14px', fontWeight: '600',
          text: String(label + 1 ) }
      });

      that.selectedMarkers.push({ id: item.id , marker: gMarker });
    }
    else{
      var Icono = {
        path: google.maps.SymbolPath.CIRCLE,
        fillOpacity: 1,
        fillColor: markColor,
        strokeOpacity: 1,
        strokeWeight: 2,
        strokeColor: '#fff',
        scale: 6
      };
      var gMarker = new google.maps.Marker({
        map: that.map,
        position: new google.maps.LatLng( item.loc.lat, item.loc.lng ),
        icon: Icono,
      });
    }

    that.infoWindow = new smart_infowindow({
      map: that.map,
      width: 300,
      max_height: 200,
      marker_distance: [12,12], // [top, bottom]
    });

    gMarker.addListener('mouseover', function() {
      var infowindowHtml = '<div class="iWindow">'+
        '<div class="image">'+
          '<img class="img-responsive" src="/cgmlImg/'+item.image+'/travelPlannerList/'+item.image+'.jpg">'+
        '</div>'+
        '<div class="info">'+
          '<div class="title">'+item.title+'</div>'+
          '<div class="description">'+item.shortDescription+'</div>'+
        '</div>'+
      '</div>';
      that.infoWindow.open(gMarker, 'mouseover' , infowindowHtml);
    });

    gMarker.addListener('mouseout', function() {
      setTimeout(
        function() {
          if(smart_infowindow_is_on_infowindow == false) {
            that.infoWindow.close();
          }
        }
      , 10 );
    });

    that.markers.push({ id: item.id , marker: gMarker });
  },
  startRouteOnMap: function(optimize){
    var that = this;
    var resSelectedInDay = [];
    $(that.parentTp.tpData.get('list')).each( function(iday,day) {
      $(day).each( function(i,item){
        if( that.currentDay  == iday ){
          resSelectedInDay.push(item.id);
        }
      });
    });
    var resourcesToList = [];
    resourcesToList = that.parentTp.resources;
    resourcesToList = new geozzy.collection.ResourceCollection( resourcesToList.filterById(resSelectedInDay) );

    var resSelectedInDayData = [];
    $.each( resSelectedInDay, function(i ,item){
      resSelectedInDayData.push(resourcesToList.get(item).toJSON());
    });

    if(resSelectedInDayData.length > 1){
      that.calcRoute(resSelectedInDayData, optimize);
    }else{
      that.clearRoute();
    }
  },

  removeMarkers: function(){
    var that = this;
    $.each( that.markers, function(i ,el){
      el.marker.setMap( null );
    });
  },
  highlightMarker: function(resId){
    var that = this;
    $.each(that.markers, function( i, el ) {
      if(el.id === parseInt(resId)) {
        //el.marker.setAnimation(google.maps.Animation.BOUNCE);
        el.marker.setOptions({
          title: 'selectedMarker'
        });
        el.marker.setMap( null );
        el.marker.setMap( that.map );
        return false;
      }
    });
  },
  stopHighlightMarker: function(resId){
    var that = this;
    $.each(that.markers, function( i, el ) {
      if(el.id === parseInt(resId)) {
        //el.marker.setAnimation(null);
        el.marker.setOptions({
          title: ''
        });
        el.marker.setMap( null );
        el.marker.setMap( that.map );
        return false;
      }
    });
  },
  calcRoute: function( dataPoints, optimize ){
    var that = this;
    var firstLoc = false;
    var lastLoc = false
    var waypointsLoc = [];

    var optimizeRes = (optimize) ? true : false;

    if(that.directionsService === false){
      that.directionsService = new google.maps.DirectionsService();
    }
    if(that.directionsDisplay === false){
      that.directionsDisplay = new google.maps.DirectionsRenderer({ suppressMarkers: true });
    }else{
      that.clearRoute();
    }

    that.directionsDisplay.setMap(that.map);

    if(dataPoints.length > 1){
      //Generamos array con las coords
      $.each(dataPoints, function( i, el ) {
        waypointsLoc.push({ location: el.loc });
      });

      //Extraemos el inicio y fin de ruta
      firstLoc = waypointsLoc.shift();
      lastLoc = waypointsLoc.pop();

      if(cogumelo.publicConf.mod_geozzy_travelPlanner.routeMode && cogumelo.publicConf.mod_geozzy_travelPlanner.routeMode === 'WALKING'){
        confTravelMode = google.maps.DirectionsTravelMode.WALKING;
      }else{
        confTravelMode = google.maps.DirectionsTravelMode.DRIVING;
      }

      var request = {
        origin: firstLoc.location,
        destination: lastLoc.location,
        waypoints: waypointsLoc,
        optimizeWaypoints: optimizeRes,
        travelMode: confTravelMode
      };
      if( !that.directionsServiceRequest[that.currentDay] || optimizeRes){
        //console.log("petición dia: "+that.currentDay);
        that.directionsService.route(request, function(response, status) {
          if (status == google.maps.DirectionsStatus.OK) {
            that.directionsServiceRequest[that.currentDay] = response;
            if(optimizeRes){
              var list = that.parentTp.tpData.get('list');
              var resourcesDay = list[that.currentDay];
              var new_day = [];
              var waypointsOptmz = [];
              //Reordenamos los recursos del dia actual según la optimización
              new_day[0] = resourcesDay[0];
              $.each( response['routes'][0]['waypoint_order'], function( i, w ){
                waypointsOptmz[i] = waypointsLoc[w];
                new_day[(i+1)] = resourcesDay[(w+1)];
              });
              new_day[(response['routes'][0]['waypoint_order'].length+1)] = resourcesDay[(response['routes'][0]['waypoint_order'].length+1)];
              list[that.currentDay] = new_day;
              //Pasamos el listado de dias al tpPlan para que lo guarde
              that.parentTp.travelPlannerPlanView.reorderDay( list );

              that.printRouteOnMaps(response, waypointsOptmz);
            }else{
              that.printRouteOnMaps(response, waypointsLoc);
            }
          }
          else {
            console.log("directions status "+status);
          }
        });
      }else{
        //console.log("petición en cache dia : "+that.currentDay);
        that.printRouteOnMaps(that.directionsServiceRequest[that.currentDay], waypointsLoc);
      }

    }
  },
  printRouteOnMaps: function printRouteOnMaps( response , waypoints ){
    var that = this;
    var totalTimeTransport = 0;

    that.directionsDisplay.setDirections( response );
    that.tramoExtraArray = [];
    that.tramoExtraArray.push(that.tramoExtra( response.request.origin.location, response.routes[0].legs[0].start_location ));
    that.parentTp.travelPlannerPlanView.clearTransportTimes();
    that.parentTp.travelPlannerPlanView.addRouteTimeRes( that.currentDay , '' , 0 );
    $.each(response.routes[0].legs, function( i, leg ) {
      totalTimeTransport += leg.duration.value;
      that.parentTp.travelPlannerPlanView.addRouteTimeRes( that.currentDay , leg.duration.text , (i+1) );
      if( (i+1) !== response.routes[0].legs.length ){
        that.tramoExtraArray.push(
          that.tramoExtra( new google.maps.LatLng( waypoints[i].location.lat, waypoints[i].location.lng ), leg.end_location )
        );
      }else{
        //Ultimo
        that.tramoExtraArray.push( that.tramoExtra( response.request.destination.location, leg.end_location ) );
      }
    });
    that.parentTp.travelPlannerPlanView.addRouteTotalTime( that.currentDay , totalTimeTransport/60 );
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
    // borra ruta del mapa
    if( that.directionsDisplay ) {
      //that.directionsDisplay.setDirections( {routes: []} );
      that.directionsDisplay.setMap(null);
    }
    if( that.tramoExtraArray && that.tramoExtraArray.length > 0 ) {
      $.each( that.tramoExtraArray, function( i, tramo ) {
        if(tramo){
          tramo.setMap( null );
        }
      });
      that.tramoExtraArray = [];
    }
  },
  clearDirectionsServiceRequest: function(days){
    var that = this;
    console.log("clearDirectionsServiceRequest", days );
    if(days){
      $.each( days, function(i,d){
        that.directionsServiceRequest[d] = '';
      });
    }else{
      that.directionsServiceRequest = [];
    }
  }

});
