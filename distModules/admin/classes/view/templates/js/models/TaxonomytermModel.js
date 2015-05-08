var TaxonomytermModel = Backbone.Model.extend({
  defaults: {
    id: false,
    parent: 0,
    weight:0,
    taxgroup: false,
    deleted: 0
  }

});
