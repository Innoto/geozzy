var geozzy = geozzy || {};
if (! geozzy.travelPlannerComponents) { geozzy.travelPlannerComponents= {} }
geozzy.travelPlannerComponents.mainRouter = Backbone.Router.extend({
  routes: {
    'travelPlanner': 'travelPlanner'
  },
  travelPlanner: function() {
    if( window.location.pathname === "/travelPlanner" ){
      alert('asd');
      geozzy.travelPlannerInstance.init();
    }
  }
});
