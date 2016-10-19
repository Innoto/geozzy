var geozzy = geozzy || {};
if(!geozzy.travelPlannerComponents) geozzy.travelPlannerComponents={};

geozzy.travelPlannerComponents.TravelPlannerInterfaceView = Backbone.View.extend({

  interfaceTemplate : false,
  resourceTemplate : false,
  parentTp : false,

  events: {
    "change .travelPlannerFilters .filterByFavourites": "changeFilters",
    "change .travelPlannerFilters .filterByRtype": "changeFilters",
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
    that.listResources();
  },

  loadInterfaceTravelPlanner: function(){
    var that = this;
    that.interfaceTemplate = _.template( $('#travelPlannerInterfaceTemplate').html() );

    var rtypesFilters = [];
    _.each( cogumelo.publicConf.mod_geozzy_travelPlanner, function(item){
      rtypesFilters.push(that.parentTp.rtypes.where({ idName: item })[0].toJSON());
    });
    that.$el.html( that.interfaceTemplate({ rtypesFilters: rtypesFilters }) );

    that.render();
  },
  listResources: function(){
    var that = this;

    var filterByRtype = that.$el.find('select.filterByRtype').val();
    var filterByFavourites = that.$el.find('select.filterByFavourites').val();
    var resourcesToList = [];

    resourcesToList = that.parentTp.resources;

    if(filterByFavourites === "fav"){
      resourcesToList = new geozzy.collection.ResourceCollection( resourcesToList.filterById(that.parentTp.favResources) );
    }
    if(filterByRtype !== "*"){
      resourcesToList = new geozzy.collection.ResourceCollection( resourcesToList.where({ rTypeIdName: filterByRtype }) );
    }
    that.$el.find('.travelPlannerResources').html('');
    $.each( resourcesToList.toJSON(), function(i ,item){
      that.resourceTemplate = _.template( $('#resourceItemTPTemplate').html() );
      that.$el.find('.travelPlannerResources').append(that.resourceTemplate({ resource: item }));
    });
  },
  changeFilters: function(e){
    var that = this;
    that.render();
  }
});
