var geozzy = geozzy || {};
if(!geozzy.travelPlannerComponents) geozzy.travelPlannerComponents={};

geozzy.travelPlannerComponents.TravelPlannerInterfaceView = Backbone.View.extend({
  el: "#travelPlannerSec",
  interfaceTemplate : false,
  resourceTemplate : false,
  parentTp : false,

  events: {
    "click .travelPlannerFilterBar .filterByFavourites": "changeFilters",
    "change .travelPlannerFilterBar .filterByRtype": "changeFilters",
    "mouseover .tpResourceItem": "resourceMouseenter",
    "mouseleave .tpResourceItem": "resourceMouseleave",
    "click .addToPlan": "addToPlan",
    "click .tp-gotoPlan": "goToPlan",
    "click .tp-goAddtoPlan": "goAddToPlan",
    "click .travelPlannerHelp": "getHelp",
    "click .travelPlannerFilterBar .days .filterDay": "filterDay",

    "click .travelPlannerMobile .tp-gotoMobilePlan": "goMobilePlan",
    "click .travelPlannerMobile .tp-gotoMobileList": "goMobileList",
    "click .travelPlannerMobile .tp-gotoMobileChangeDates": "goMobileDates"
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

    if(cogumelo.publicConf.mod_detectMobile_isMobile){
      that.changeTravelPlannerInterfaceMobile(that.parentTp.travelPlannerMode);
    }else{
      that.changeTravelPlannerInterface(that.parentTp.travelPlannerMode);
    }
  },

  render: function() {
    var that = this;
  },

  loadInterfaceTravelPlanner: function(){
    var that = this;
    if(cogumelo.publicConf.mod_detectMobile_isMobile){
      that.interfaceTemplate = _.template( $('#travelPlannerInterfaceMobileTemplate').html() );
    }else{
      that.interfaceTemplate = _.template( $('#travelPlannerInterfaceTemplate').html() );
    }

    var rtypesFilters = [];
    _.each( cogumelo.publicConf.geozzyTravelPlanner.rTypes, function(item, i){
      rtypesFilters.push(that.parentTp.rtypes.where({ idName: item })[0].toJSON());
    });
    that.$el.html( that.interfaceTemplate({ rtypesFilters: rtypesFilters }) );

    that.render();
  },
  listResources: function(){
    var that = this;

    var filterByRtype = that.$('select.filterByRtype').val();
    var filterByFavourites = that.$('.filterByFavourites').hasClass('active') ? true : false;
    var resourcesToList = [];

    resourcesToList = that.parentTp.resources;

    if(filterByFavourites === true){
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

    that.parentTp.travelPlannerMapView.showMarkers( resourcesToList.pluck('id')  );
  },
  resourceMouseenter: function(e) {
    var that = this;
    that.parentTp.travelPlannerMapView.markerBounce($(e.currentTarget).attr('data-resource-id'));

  },
  resourceMouseleave: function(e) {
    var that = this;
    that.parentTp.travelPlannerMapView.markerBounceEnd($(e.currentTarget).attr('data-resource-id'));
  },
  //Bind para cuando cambia un filtro
  changeFilters: function(e){
    var that = this;
    if($(e.currentTarget).hasClass('filterByFavourites')){
      if(that.$('.filterByFavourites').hasClass('active')){
        that.$('.filterByFavourites').removeClass('active');
      }else{
        that.$('.filterByFavourites').addClass('active');
      }
    }
    that.listResources();
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

  //Bind para cambiar a modo 2
  getHelp: function(e){
    var that = this;
    that.parentTp.helpTp(false);
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
      that.$el.find('.travelPlannerFilterBar .mode').hide();
      that.$el.find('.travelPlannerFilterBar .mode'+mode).show();

      that.parentTp.travelPlannerMapView.setInitMap();
      that.listResources();

    }
    else{
      if( that.parentTp.tpData.get('checkin') !== null || that.parentTp.tpData.get('checkout') !== null ){
        that.$el.find('.tp-gotoPlan').hide();
        that.$el.find('.tp-goAddtoPlan').show();
        that.$el.find('.travelPlannerList').hide();
        that.$el.find('.travelPlannerPlan').show();
        that.$el.find('.travelPlannerMap').hide();
        that.$el.find('.travelPlannerMapPlan').show();
        that.$el.find('.travelPlannerFilterBar .mode').hide();
        that.$el.find('.travelPlannerFilterBar .mode'+mode).show();
        that.parentTp.travelPlannerMapPlanView.showDay(that.parentTp.travelPlannerMapPlanView.currentDay);
        that.drawFilterDay();
      }
      else{
        that.parentTp.getDates();
      }
    }
  },
  drawFilterDay: function(){
    var that = this;
    that.$el.find('.travelPlannerFilterBar .mode2 .days').html('');
    var checkin =  that.parentTp.momentDate( that.parentTp.tpData.get('checkin') );
    var checkout = that.parentTp.momentDate( that.parentTp.tpData.get('checkout') );
    var planDays = 1 + checkout.diff( checkin, 'days');
    for (var i = 0; i < planDays; i++) {
      that.$el.find('.travelPlannerFilterBar .mode2 .days').append('<li class="filterDay filterDay-'+i+'" data-day="'+i+'">'+__("Day")+'<span> '+parseInt(i+1)+'</span></li>');
    }
  },
  filterDay: function(e){
    var that = this;
    var day = $(e.currentTarget).attr('data-day');
    that.parentTp.travelPlannerMapPlanView.currentDay = day;
    that.parentTp.travelPlannerMapPlanView.showDay(that.parentTp.travelPlannerMapPlanView.currentDay);
    $('html,body').animate({scrollTop: $('#plannerDay-'+day).offset().top},'slow');
  },
  changeTravelPlannerInterfaceMobile: function(mode){
    var that = this;

    switch (mode) {
      case 1:
        //Lista
        that.$el.find('.tp-gotoMobilePlan').show();
        //that.$el.find('.tp-gotoMobileMap').show();
        that.$el.find('.tp-gotoMobileList').hide();
        that.$el.find('.tp-gotoMobileChangeDates').show();

        that.$el.find('.travelPlannerPlan').hide();
        that.$el.find('.travelPlannerMapPlan').hide();
        that.$el.find('.travelPlannerList').show();
        that.$el.find('.travelPlannerFilterBar .mode').hide();
        that.$el.find('.travelPlannerFilterBar .mode'+mode).show();
        that.listResources();
        break;
      case 3:
        //Mapa
        if( that.parentTp.tpData.get('checkin') !== null || that.parentTp.tpData.get('checkout') !== null ){
          that.$el.find('.tp-gotoMobilePlan').show();
          //that.$el.find('.tp-gotoMobileMap').hide();
          that.$el.find('.tp-gotoMobileList').hide();
          that.$el.find('.tp-gotoMobileChangeDates').hide();

          that.$el.find('.travelPlannerPlan').hide();
          that.$el.find('.travelPlannerList').hide();
          that.$el.find('.travelPlannerMapPlan').show();
          that.$el.find('.travelPlannerFilterBar .mode').hide();
          // /that.$el.find('.travelPlannerFilterBar .mode'+mode).show();
          that.parentTp.travelPlannerMapPlanView.showDay(that.parentTp.travelPlannerMapPlanView.currentDay);
        }else{
          that.parentTp.getDates();
        }
        break;
      case 2:
      default:
        //Plan
        if( that.parentTp.tpData.get('checkin') !== null || that.parentTp.tpData.get('checkout') !== null ){
          that.$el.find('.tp-gotoMobilePlan').hide();
          //that.$el.find('.tp-gotoMobileMap').show();
          that.$el.find('.tp-gotoMobileList').show();
          that.$el.find('.tp-gotoMobileChangeDates').show();

          that.$el.find('.travelPlannerList').hide();
          that.$el.find('.travelPlannerPlan').show();
          that.$el.find('.travelPlannerMapPlan').hide();
          that.$el.find('.travelPlannerFilterBar .mode').hide();
          that.$el.find('.travelPlannerFilterBar .mode'+mode).show();
          that.parentTp.travelPlannerMapPlanView.showDay(that.parentTp.travelPlannerMapPlanView.currentDay);
          that.drawFilterDay();
        }
        else{
          that.parentTp.getDates();
        }
    }
  },
  goMobilePlan: function(e){
    var that = this;
    geozzy.travelPlannerComponents.routerInstance.navigate("plan", {trigger: true});

  },
  goMobileList: function(e){
    var that = this;
    geozzy.travelPlannerComponents.routerInstance.navigate("list", {trigger: true});

  },
  goMobileMap: function(e){
    var that = this;
    geozzy.travelPlannerComponents.routerInstance.navigate("map", {trigger: true});

  },
  goMobileDates: function(e){
    var that = this;
    that.parentTp.getDates();
  }
});
