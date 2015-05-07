var CategorytermCollection = Backbone.Collection.extend({
  url: '/api/admin/categoryterms',
  model: TaxonomytermModel,
  sortKey: 'id',

  search: function( opts ){

    var result = this.where( opts );
    var resultCollection = new CategorytermCollection( result );

    return resultCollection;
  },
  comparator: function(item){
    return item.get(this.sortKey);
  },
  sortByField: function(fieldName) {
    this.sortKey = fieldName;
		this.sort();
  },

  save: function(){

    var mA = [];

    _(this.models).each( function(m) {
      mA.push(m);
    } );

    _(mA).each( function(m2) {

      if( m2.get('deleted') == 1 ){
        m2.destroy();
      }
      else {
        m2.save();
      }
    });
  }
});
