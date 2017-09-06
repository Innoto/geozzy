var geozzy = geozzy || {};
if(!geozzy.travelPlannerComponents) geozzy.travelPlannerComponents={};

geozzy.travelPlannerComponents.TravelPlannerInterfaceView = Backbone.View.extend({
  el: "#travelPlannerSec",
  interfaceTemplate : false,
  resourceTemplate : false,
  parentTp : false,

  events: {
    "change .travelPlannerFilters .filterByFavourites": "changeFilters",
    "change .travelPlannerFilters .filterByRtype": "changeFilters",
    "click .addToPlan": "addToPlan"
  },

  initialize: function( parentTp ) {
    var that = this;

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

    var filterByRtype = that.$('select.filterByRtype').val();
    var filterByFavourites = that.$('select.filterByFavourites').val();
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
      if (typeof(item.rextmodels.RExtVisitDataModel) != "undefined") {
        var duration = item.rextmodels.RExtVisitDataModel.duration;
        var duration_h = Math.floor( duration / 60 );
        var duration_m = duration % 60;
        if( duration_m > 0 ){
          item.defaultDuration = duration_m+'min';
        }
        if( duration_h > 0){
          if( typeof(item.defaultDuration) == 'undefined'){
            item.defaultDuration = duration_h+'h';
          }else{
            item.defaultDuration = duration_h+'h '+item.defaultDuration;
          }

        }

      }
      that.resourceTemplate = _.template( $('#resourceItemTPTemplate').html() );
      that.$('.travelPlannerResources').append(that.resourceTemplate({ resource: item }));
    });
  },
  changeFilters: function(e){
    var that = this;
    that.render();
  },
  addToPlan: function(e){
    var that = this;
    that.parentTp.addToPlan($(e.target).closest('.tpResourceItem').attr('data-resource-id'));
  }

});
