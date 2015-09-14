var geozzy = geozzy || {};
if(!geozzy.filters) geozzy.filters={};


geozzy.filters.filterValue = geozzy.filter.extend({
  filter: function( model ) {
    var terms =  model.get('terms');

    var diff = $( terms ).not( this.data );
    return (diff.length != terms.length );

  }
});
