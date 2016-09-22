var geozzy = geozzy || {};

geozzy.story = function() {

  var that = this;
  that.listStoryView = false;

  // First Execution

  that.listStories = function( ){
    that.listStoryView = new geozzy.adminStoryComponents.ListStoryView();
  }

}
