var ResourcesStarredCollection = Backbone.Collection.extend({
  url: '/api/admin/resourcesTerm/',
  model: ResourceModel,
  sortKey: 'weight',

  search: function( opts ){

    var result = this.where( opts );
    var resultCollection = new ResourcesStarredCollection( result );

    return resultCollection;
  },
  comparator: function(item){
    return item.get(this.sortKey);
  },
  sortByField: function(fieldName) {
    this.sortKey = fieldName;
		this.sort();
  },

  save: function(taxonomyterm){

    var mA = [];

    _(this.models).each( function(m) {
      mA.push(m);
    } );

    _(mA).each( function(m2) {

      if( m2.get('deleted') == 1 ){
        //cogumelo.log(m2.toJSON());
        m2.destroy();
      }
      else {
        m2.save();
      }
    });
  }
});
