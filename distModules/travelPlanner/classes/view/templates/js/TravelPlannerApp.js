var geozzy = geozzy || {};


geozzy.travelPlanner = function() {

  var that = this;

  that.travelPlannerRouter = false;

  // First Execution
  //
  that.init = function(  ) {

  }

  that.travelPlannerRouter = new geozzy.travelPlannerComponents.mainRouter();

  $(document).ready( function(){
    if( !Backbone.History.started ){
      Backbone.history.start();
    }
  });
}
