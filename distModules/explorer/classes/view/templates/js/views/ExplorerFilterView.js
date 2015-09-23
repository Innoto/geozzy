var geozzy = geozzy || {};

geozzy.filter = Backbone.View.extend({
  parentExplorer: false,
  data: false,

  template: false,

  options:  {
    title: false,
    DOMId: false,
    DOMContainer: false
  },

  initialize: function( opts ) {
    this.options = opts;
  },

  filterAction: function() {
    return true;
  },

  // util functions
  arrayInArray: function( array1, array2 ) {
    var matches = false;

    $.each(array1, function(i,e) {
      if( $.inArray( e, array2) == -1 ) {
        matches = true;
        return;
      }
    });

    return matches;
  },

  render: function() {

  },

  show: function() {

  },

  hide: function() {

  }


});
