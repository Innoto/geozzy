var geozzy = geozzy || {};

geozzy.filterView = Backbone.View.extend({

  parentExplorer: false,
  selectedTerms: false,
  template: false,
  title : false,
  data: false,

  options:  {
    title: false,
    containerClass: false,
    mainCotainerClass: false,
    data:false,
    title:false,
    afterRender:false
  },

  initialize: function( opts ) {
    this.options = $.extend(true, {}, this.options, opts);
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


  getSelectedTerms: function() {
    var retTerms = [];

    if( $.isArray( this.selectedTerms ) ) {
      retTerms = this.selectedTerms;
    }
    else if( this.selectedTerms != false ) {
      retTerms.push( this.selectedTerms );
    }

    return retTerms;
  },

  reset: function() {

  },

  render: function() {

  },

  show: function() {

  },

  hide: function() {

  }



});
