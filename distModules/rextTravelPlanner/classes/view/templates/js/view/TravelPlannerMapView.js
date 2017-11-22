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
  }

});
