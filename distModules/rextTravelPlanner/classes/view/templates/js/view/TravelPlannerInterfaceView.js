var geozzy = geozzy || {};
if(!geozzy.travelPlannerComponents) geozzy.travelPlannerComponents={};

geozzy.travelPlannerComponents.TravelPlannerInterfaceView = Backbone.View.extend({

  interfaceTemplate : false,
  resourceTemplate : false,
  parentTp : false,

  events: {

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
  },

  loadInterfaceTravelPlanner: function(){
    var that = this;

    that.interfaceTemplate = _.template( $('#travelPlannerInterfaceTemplate').html() );
    that.$el.html( that.interfaceTemplate );

    that.createFilters();
    that.listResources();
  },
  listResources: function(){
    var that = this;

    that.$el.find('.travelPlannerResources').html('');
    _.each( that.parentTp.resources.toJSON(), function(item){
      that.resourceTemplate = _.template( $('#resourceItemTPTemplate').html() );
      that.$el.find('.travelPlannerResources').append(that.resourceTemplate({ resource: item }));
    });
  },
  createFilters: function(){

  }
});
