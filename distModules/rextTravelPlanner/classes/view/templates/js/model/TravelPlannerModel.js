var geozzy = geozzy || {};
if(!geozzy.travelPlannerComponents) geozzy.travelPlannerComponents={};

geozzy.travelPlannerComponents.TravelPlannerModel = Backbone.Model.extend({
  defaults: {
    id: false,
    user: false,
    checkin: null,
    checkout: null,
    list: false
  },
  urlRoot: '/api/travelplanner',

  saveData: function(){
    var that = this;
  },

  fetchData: function() {
    var that = this;
    return that.fetch({
      url: that.urlRoot,
      data: {
        cmd: 'getTravelPlanner'
      },
      type: 'POST'
    });
  }
});
