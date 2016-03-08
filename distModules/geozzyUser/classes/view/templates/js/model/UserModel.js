var geozzy = geozzy || {};
if(!geozzy.model) geozzy.model={};

geozzy.model.UserModel = Backbone.Model.extend({
  defaults: {
    id: false,
    title: ''
  }
});
