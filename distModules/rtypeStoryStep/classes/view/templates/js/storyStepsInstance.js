var geozzy = geozzy || {};
$(document).ready(function(){
    if( !Backbone.History.started ){
      Backbone.history.start();
    }
    else {
      Backbone.history.stop();
      Backbone.history.start();
    }
    geozzy.storyStepsInstance = new geozzy.storysteps();
    geozzy.storyStepsInstance.listStorySteps(story);
});
