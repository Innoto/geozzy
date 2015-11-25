var geozzy = geozzy || {};
if(!geozzy.model) geozzy.model={};

geozzy.model.ResourceModel = Backbone.Model.extend({
  defaults: {
    id: false,
    title: ''
  }
});
