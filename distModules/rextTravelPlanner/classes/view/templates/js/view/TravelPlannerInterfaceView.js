var geozzy = geozzy || {};
if(!geozzy.travelPlannerComponents) geozzy.travelPlannerComponents={};

geozzy.travelPlannerComponents.TravelPlannerInterfaceView = Backbone.View.extend({

  interfaceTemplate : false,
  parentTp : false,

  events: {

  },

  loadInterfaceTravelPlanner: function(){
    var that = this;

    that.interfaceTemplate = _.template( geozzy.travelPlannerComponents.travelPlannerInterfaceTemplate );
    that.$el.html( that.interfaceTemplate );

    console.log(that.parentTp.resources);
    console.log(that.parentTp.favResources);
  },

  initialize: function( parentTp ) {
    var that = this;

    that.el = "#travelPlannerSec";
    that.$el = $(that.el);
    that.delegateEvents();
    that.parentTp = parentTp;

    that.loadInterfaceTravelPlanner();
  },

  render: function() {
    var that = this;

    //that.$el.html( that.tpl({ content: contentHtml }) )
  }

});
