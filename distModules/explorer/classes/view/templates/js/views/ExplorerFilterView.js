var geozzy = geozzy || {};

geozzy.filter = Backbone.View.extend({
  parentExplorer: false,
  selectedData: false,
  template: false,
  title : false,
  data: false,

  options:  {
    title: false,
    DOMId: false,
    containerQueryDiv: false
  },

  initialize: function( opts ) {
    this.options = opts;
    if( opts.data ) {
      this.data = opts.data;
    }
    if( opts.title ) {
      this.title = opts.title;
    }
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
