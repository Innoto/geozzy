var geozzy = geozzy || {};
if(!geozzy.biMetricsComponents) geozzy.biMetricsComponents={};




geozzy.biMetricsComponents.biRecommender = Backbone.Collection.extend({

  initialize: function( options ) {
    var that = this;
    var opts = {

    }

    that.options = $.extend(true, {}, opts, options );

    $.ajax({
      url:'/api/core/bi',
      cache: true,
      success: function( dat ){
        that.biApiConf = dat;
        that.syncEnable();

        // leave page event
        $( window ).unload(function() {
          that.sync();
        });

      }
    });
  },

  resource: function( resourceID, successCallback ) {

  },

  explorer: function( explorerID, bounds, successCallback ) {

  }


});
