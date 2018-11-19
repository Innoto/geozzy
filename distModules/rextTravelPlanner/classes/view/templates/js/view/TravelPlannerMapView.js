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
        styles : estilosMapa,
        gestureHandling: 'greedy'
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

    that.infoWindow = new smart_infowindow({
      map:that.map,
      width: 300,
      max_height:200,
      marker_distance: [12,12], // [top, bottom]
    });

    that.parentTp.resources.each( function(e,i) {

      var markerLoc = e.get('loc');
      //cogumelo.log( e.get('loc') );
      if( markerLoc!= null && typeof markerLoc.lat != 'undefined' && typeof markerLoc.lng != 'undefined' ) {

        var marker = new google.maps.Marker({
          position: new google.maps.LatLng( markerLoc.lat, markerLoc.lng ),
          icon: that.markerIcon
        });

        marker.addListener('mouseover', function() {
          var infowindowHtml = '<div class="iWindow">'+
            '<div class="image">'+
              '<img class="img-fluid" src="/cgmlImg/'+e.get('image')+'/travelPlannerList/'+e.get('image')+'.jpg">'+
              '<button class="addToPlan btn btn-primary"><i class="far fa-calendar-plus" aria-hidden="true"></i></button>'+
              '<a href="#resource/'+e.get('id')+'">'+
                '<button class="openResource btn btn-default"><i class="fas fa-search" aria-hidden="true"></i></i></button>'+
              '</a>'+
            '</div>'+
            '<div class="info">'+
              '<div class="title">'+e.get('title')+'</div>'+
              '<div class="description">'+e.get('shortDescription')+'</div>'+
            '</div>'+
          '</div>';
          that.infoWindow.open(marker, 'mouseover' , infowindowHtml);

          $('.iWindow .addToPlan').on('click', function(ev){
            that.addToPlan( e.get('id') );
          });
        });

        marker.addListener('mouseout', function() {
          //that.infoWindow.close(marker);
          setTimeout(
            function() {
              //smart_infowindow_click_event_opened = false;
              if(smart_infowindow_is_on_infowindow == false) {
                that.infoWindow.close();
              }
            },
            10 );
        });

        e.set('marker', marker );
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
          //cogumelo.log('seleccionado'+e.get('id'), selectedList)
          currentMarker.setIcon( that.getICon(e.get('id'), true) );
          currentMarker.setMap(that.map);
        }
        else
        if( jQuery.inArray( e.get('id'), visibleList )  != -1 ) {  // in list
          //cogumelo.log('visible'+e.get('id'), visibleList)
          currentMarker.setIcon( that.getICon(e.get('id'), false) );
          currentMarker.setMap(that.map);
        }
        else{ // not in list
          //cogumelo.log('oculto'+e.get('id'))
          currentMarker.setMap(null);
        }
      }
    });

  },

  getICon: function( resourceId, selected ) {
    var that = this;
    var retObj = false;
    var iconUrl = false;
    //cogumelo.log(that.parentTp.resources.get(resourceId).get('categoryIds') );
    //currentCategory

    if(selected) {
      var iconProfile = cogumelo.publicConf.geozzyTravelPlanner.markerImgProfileSelected;
    }
    else {
      var iconProfile = cogumelo.publicConf.geozzyTravelPlanner.markerImgProfile;
    }

    $.each(that.parentTp.categories, function(i,e) {
      e.each( function(e2,i2) {
        //cogumelo.log( e2.id );

        if(
          jQuery.inArray( e2.id , that.parentTp.resources.get(resourceId).get('categoryIds')  )  != -1 &&
          e2.get('iconAKey') &&
          e2.get('icon')
        ) {
          //cogumelo.log(resourceId,iconProfile, cogumelo.publicConf.mediaHost+'cgmlImg/'+e2.get('icon')+'-a'+e2.get('iconAKey')+'/'+iconProfile+'/marker.png')
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

  addToPlan: function(id) {
    var that= this;
    that.parentTp.addToPlan(id);
  },



  markerBounce: function(id) {
    var that = this;

    var selectedMarker = that.parentTp.resources.get(id).get('marker');
    if( selectedMarker ) {

      /*selectedMarker.setOptions({
        title: 'selected'
      });*/
      selectedMarker.setAnimation(google.maps.Animation.BOUNCE);
    }

/*
    // set as selected
    var selectedMarker = that.parentTp.resources.get(id).get('marker');
    if( selectedMarker ) {
      //selectedMarker.setMap(that.map)
      selectedMarker.setOptions({
        title: 'selected'
      });
      cogumelo.log(selectedMarker);
    }*/
  },
  markerBounceEnd: function(id) {
    var that = this;
    var selectedMarker = that.parentTp.resources.get(id).get('marker');
    if( selectedMarker ) {

      /*selectedMarker.setOptions({
        title: 'selected'
      });*/
      selectedMarker.setAnimation(null);

    }
/*
    // set as selected
    var selectedMarker = that.parentTp.resources.get(id).get('marker');
    if( selectedMarker ) {
      //selectedMarker.setMap(null)
      selectedMarker.setOptions({
        title: 'selected'
      });
    }*/
  }


});
