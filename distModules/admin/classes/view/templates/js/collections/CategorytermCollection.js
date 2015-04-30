var CategorytermCollection = Backbone.Collection.extend({
  url: '/admin/categoryterms',
  model: TaxonomytermModel,
  sortKey: 'id',
  search: function( opts ){

    var result = this.where( opts );
    var resultCollection = new CategorytermCollection( result );

    return resultCollection;
  },
  comparator: function(item){
    return !item.get(this.sortKey);
  },
  sortByField: function(fieldName) {
    this.sortKey = fieldName;
		this.sort();
  }
});
