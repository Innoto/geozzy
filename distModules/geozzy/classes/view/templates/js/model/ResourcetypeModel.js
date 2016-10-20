var geozzy = geozzy || {};
if(!geozzy.model) geozzy.model={};

geozzy.model.ResourcetypeModel = Backbone.Model.extend({
  defaults: {
    id: false,
    idName: '',
    name: '',
    icon: ''
  }
});
