var CategorytermCollection = Backbone.Collection.extend({
  url: '/admin/categoryterms',
  model: TaxonomytermModel,
  search: function( opts ){

    var result = this.where( opts );
    var resultCollection = new CategorytermCollection( result );

    return resultCollection;
  }
});