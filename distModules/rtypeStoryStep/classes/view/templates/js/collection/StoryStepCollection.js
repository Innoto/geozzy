var geozzy = geozzy || {};
if(!geozzy.storystepsComponents) geozzy.storystepsComponents={};

geozzy.storystepsComponents.StorystepCollection = Backbone.Collection.extend({
  baseUrl: '/api/admin/adminStorySteps',
  url: false,
  model: ResourceModel,
  sortKey: 'weight',

  search: function( opts ){
    var result = this.where( opts );
    //cogumelo.log(result);
    var resultCollection = new geozzy.storystepsComponents.StorystepCollection( result );


    return resultCollection;
  },
  comparator: function(item){
    return item.get(this.sortKey);
  },
  sortByField: function(fieldName) {
    this.sortKey = fieldName;
		this.sort();
  },
  fetchById: function(id, fetchByIdSuccess){
    this.url = this.baseUrl + '/resource/'+id ;
    var that = this;
    that.fetch({
      success: function() {
        fetchByIdSuccess();
      }
    });

  },

  save: function(story){
    var that = this;
    var mA = [];
    _(this.models).each( function(m) {
      mA.push(m);
    } );

    _(mA).each( function(m2) {
      if( m2.get('deleted') == 1 ){
        m2.destroy( { url: that.baseUrl + '/resource/'+story + '/step/' + m2.get('id') } );
      }
      else {
        m2.save();
      }
    });
  }
});
