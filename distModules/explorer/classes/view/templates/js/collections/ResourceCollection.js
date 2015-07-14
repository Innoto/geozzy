var ResourceCollection = Backbone.Collection.extend({
  url: false,
  model: ResourceModel,
  localStorage: new Backbone.LocalStorage("ResourceCollection")
});
