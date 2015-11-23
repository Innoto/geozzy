var geozzy = geozzy || {};
if(!geozzy.collections) geozzy.models={};

geozzy.models.ResourceModel = Backbone.Model.extend({
  defaults: {
    id: false,
    title: ''
  }
});
