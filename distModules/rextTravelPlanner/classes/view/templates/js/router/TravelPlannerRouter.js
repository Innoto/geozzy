var geozzy = geozzy || {};
if (! geozzy.storyComponents) { geozzy.storyComponents= {}; }

geozzy.travelPlannerComponents.mainRouter = Backbone.Router.extend({
  parentTp: false,
  routes: {
    '': 'main',
    'resource/:id': 'resource',
    'list': 'goMobileList',
    'plan': 'goMobilePlan',
    'map': 'goMobileMap'
  },


  main: function( ) {
    var that = this;
    that.parentTp.closeResource();
  },
  resource: function( id ) {
    var that = this;
    that.parentTp.openResource(id);
  },
  goMobileList: function() {
    var that = this;
    if(cogumelo.publicConf.mod_detectMobile_isMobile){
      that.parentTp.travelPlannerMode = 1;
      that.parentTp.travelPlannerInterfaceView.changeTravelPlannerInterfaceMobile(that.parentTp.travelPlannerMode);
    }
  },
  goMobilePlan: function() {
    var that = this;
    if(cogumelo.publicConf.mod_detectMobile_isMobile){
      that.parentTp.travelPlannerMode = 2;
      that.parentTp.travelPlannerInterfaceView.changeTravelPlannerInterfaceMobile(that.parentTp.travelPlannerMode);
    }
  },
  goMobileMap: function() {
    var that = this;
    if(cogumelo.publicConf.mod_detectMobile_isMobile){
      that.parentTp.travelPlannerMode = 3;
      that.parentTp.travelPlannerInterfaceView.changeTravelPlannerInterfaceMobile(that.parentTp.travelPlannerMode);
    }
  }
});
