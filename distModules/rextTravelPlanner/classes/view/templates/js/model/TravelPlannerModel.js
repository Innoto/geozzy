var geozzy = geozzy || {};
if(!geozzy.travelPlannerComponents) geozzy.travelPlannerComponents={};

geozzy.travelPlannerComponents.TravelPlannerModel = Backbone.Model.extend({
  defaults: {
    id: false,
    initDate: false,
    endDate: false,
    list: false
  },
  urlRoot: '/api/travelplanner'
});
