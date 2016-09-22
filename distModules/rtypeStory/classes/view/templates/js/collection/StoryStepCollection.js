var geozzy = geozzy || {};
if(!geozzy.storyComponents) geozzy.storyComponents={};

geozzy.storyComponents.StoryStepCollection = Backbone.Collection.extend({
  url: '/api/admin/adminStories',
  model: geozzy.storyComponents.StoryStepModel
});
