var geozzy = geozzy || {};
if(!geozzy.travelPlannerComponents) geozzy.travelPlannerComponents={};

geozzy.travelPlannerComponents.TravelPlannerInterfaceView = Backbone.View.extend({

  interfaceTemplate : false,

  events: {

  },


  loadInterfaceTravelPlanner: function(){
    
    var that = this;

    that.interfaceTemplate = _.template( geozzy.travelPlannerComponents.travelPlannerInterfaceTemplate );

    that.$el.html( that.interfaceTemplate );

  },


  initialize: function( opts ) {
    var that = this;

    that.el = "#travelPlannerSec";
    that.$el = $(that.el);
    that.delegateEvents();

    that.loadInterfaceTravelPlanner();

  },
  render: function() {
    var that = this;
    //that.$el.html( that.tpl({ content: contentHtml }) )
  }

});
