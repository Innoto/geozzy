var TaxonomygroupModel = Backbone.Model.extend({
  defaults: {
    id: false,
    idName: '',
    name:'',
    editable: 0,
    nestable: 0,
    sortable: 0
  }
});
