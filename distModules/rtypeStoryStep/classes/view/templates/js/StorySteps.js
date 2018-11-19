var geozzy = geozzy || {};

geozzy.storysteps = function() {
  var that = this;
  that.listStoryStepView = false;

  // First Execution
  that.listStorySteps = function(story){
    that.listStoryStepView = new geozzy.storystepsComponents.ListStoryStepView(story);
  };

};
