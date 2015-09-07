var geozzy = geozzy || {};

geozzy.filter = Backbone.View.extend({

    filter: function() {
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
    }
});
