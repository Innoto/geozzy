var geozzy = geozzy || {};
if(!geozzy.storyComponents) geozzy.storyComponents={};

geozzy.storyComponents.StoryStepCollection = Backbone.Collection.extend({
  url: '',
  model: geozzy.storyComponents.StoryStepModel
});
