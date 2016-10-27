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
    return that.fetch({
      url: that.urlRoot,
      data: {
        cmd: 'setTravelPlanner',
        resourceData: that.toJSON()
      },
      type: 'POST',
      success: function(data) {
        //that.set( 'list', JSON.parse(that.get('list')) );
      }
    });
  },

  fetchData: function() {
    var that = this;
    return that.fetch({
      url: that.urlRoot,
      data: {
        cmd: 'getTravelPlanner'
      },
      type: 'POST',
      success: function(data) {
        //console.log(that.get('list'))
        that.set( 'list', JSON.parse(that.get('list')) );
      }
    });
  }
});
