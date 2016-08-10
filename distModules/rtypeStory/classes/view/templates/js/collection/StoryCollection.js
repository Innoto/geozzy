var geozzy = geozzy || {};
if(!geozzy.storyComponents) geozzy.storyComponents={};

geozzy.storyComponents.StoryCollection = Backbone.Collection.extend({
  url: '/api/admin/adminStories',
  model: ResourceModel
});
