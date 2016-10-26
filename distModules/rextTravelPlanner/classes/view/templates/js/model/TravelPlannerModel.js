var geozzy = geozzy || {};
if(!geozzy.travelPlannerComponents) geozzy.travelPlannerComponents={};

geozzy.travelPlannerComponents.TravelPlannerModel = Backbone.Model.extend({
  defaults: {
    id: false,
    user: false,
    checkin: false,
    checkout: false,
    list: false
  },
  urlRoot: '/api/travelplanner',

  save: function() {

  },

  fetch: function() {

  }
});
