var geozzy = geozzy || {};
if(!geozzy.collections) geozzy.models={};

geozzy.models.TaxonomygroupModel = Backbone.Model.extend({
  defaults: {
    id: false,
    idName: '',
    name:'',
    nestable: 0,
    sortable: 0
  }
});
