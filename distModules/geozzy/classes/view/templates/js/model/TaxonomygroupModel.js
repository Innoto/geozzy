var geozzy = geozzy || {};
if(!geozzy.model) geozzy.model={};

geozzy.model.TaxonomygroupModel = Backbone.Model.extend({
  defaults: {
    id: false,
    idName: '',
    name:'',
    nestable: 0,
    sortable: 0
  }
});
