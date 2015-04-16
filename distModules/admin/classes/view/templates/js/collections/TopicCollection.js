var TopicCollection = Backbone.Collection.extend({
  url: '/admin/topics',
  model: TopicModel
});