var geozzy = geozzy || {};
if(!geozzy.model) geozzy.model={};

geozzy.model.ResourceModel = Backbone.Model.extend({
  defaults: {
    id: false,
    weight: 0,
    title: '',
    mapVisible: 0
  }
});
