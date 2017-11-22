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
    "click .addToPlan": "addToPlan",
    "click .tp-gotoPlan": "goToPlan",
    "click .tp-goAddtoPlan": "goAddToPlan"
  },

  initialize: function( parentTp ) {
    var that = this;
    that.delegateEvents();
    that.parentTp = parentTp;

    that.loadInterfaceTravelPlanner();

    if( that.parentTp.tpData.get('list') === null ){
      that.parentTp.travelPlannerMode = 1;
    }else{
      that.parentTp.travelPlannerMode = 2;
    }
    that.changeTravelPlannerInterface(that.parentTp.travelPlannerMode);
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
  //Bind para cuando cambia un filtro
  changeFilters: function(e){
    var that = this;
    that.render();
  },
  //Bind para añadir un recurso
  addToPlan: function(e){
    var that = this;
    that.parentTp.addToPlan($(e.target).closest('.tpResourceItem').attr('data-resource-id'));
  },
  //Bind para cambiar a modo 1
  goAddToPlan: function(e){
    var that = this;
    that.parentTp.travelPlannerMode = 1;
    that.changeTravelPlannerInterface(that.parentTp.travelPlannerMode);
  },
  //Bind para cambiar a modo 2
  goToPlan: function(e){
    var that = this;
    that.parentTp.travelPlannerMode = 2;
    that.changeTravelPlannerInterface(that.parentTp.travelPlannerMode);
  },
  changeTravelPlannerInterface: function(mode){
    var that = this;
    if(mode === 1){
      that.$el.find('.tp-gotoPlan').show();
      that.$el.find('.tp-goAddtoPlan').hide();
      that.$el.find('.travelPlannerList').show();
      that.$el.find('.travelPlannerPlan').hide();
      that.$el.find('.travelPlannerMap').show();
      that.$el.find('.travelPlannerMapPlan').hide();
      that.$el.find('.travelPlannerFilterBar *').show();
      that.parentTp.travelPlannerMapView.setInitMap();
    }
    else{
      that.$el.find('.tp-gotoPlan').hide();
      that.$el.find('.tp-goAddtoPlan').show();
      that.$el.find('.travelPlannerList').hide();
      that.$el.find('.travelPlannerPlan').show();
      that.$el.find('.travelPlannerMap').hide();
      that.$el.find('.travelPlannerMapPlan').show();
      that.$el.find('.travelPlannerFilterBar *').hide();
      that.parentTp.travelPlannerMapPlanView.showDay(that.parentTp.travelPlannerMapPlanView.currentDay);
    }
  }
});
