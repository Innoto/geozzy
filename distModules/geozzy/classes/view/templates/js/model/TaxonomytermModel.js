var geozzy = geozzy || {};
if(!geozzy.model) geozzy.model={};

geozzy.model.TaxonomytermModel = Backbone.Model.extend({
  defaults: {
    id: false,
    parent: 0,
    weight:0,
    taxgroup: false,
    deleted: 0,
    icon: false,
    img: false
  }

});
