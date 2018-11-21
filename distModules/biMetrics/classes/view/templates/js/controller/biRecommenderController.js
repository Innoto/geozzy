var geozzy = geozzy || {};
if(!geozzy.biMetricsComponents) geozzy.biMetricsComponents={};




geozzy.biMetricsComponents.biRecommender = Backbone.Collection.extend({

  options: false,

  initialize: function( options ) {
    var that = this;
    var opts = {}
    that.options = $.extend(true, {}, opts, options );
  },


  getUserIdentifier: function() {
    var retID;


    if( geozzy.userSessionInstance.user.get('id')) {
      retID = geozzy.userSessionInstance.user.get('id');
    }
    else {
      retID = cogumelo.publicConf.C_SESSION_ID;
    }

    return retID;

  },

  resource: function( resourceID, successCallback ) {
    var that = this;

    geozzy.biMetricsInstances.configuration.getConf(
      function() {
        var resourceRecommenderURL = geozzy.biMetricsInstances.configuration.conf.recommends.resourceURL;

        //cogumelo.log(resourceRecommenderURL);
        $.ajax({
          url:resourceRecommenderURL,
          method: 'GET',
          data: { resourceID: resourceID, userID: that.getUserIdentifier() },
          success: function( recommendData ) {
            successCallback( recommendData );
          }
        });

      }
    );
  },

  explorer: function( explorerID, bounds, filters, successCallback ) {
    var that = this;
    geozzy.biMetricsInstances.configuration.getConf(
      function() {
        var resourceRecommenderURL = geozzy.biMetricsInstances.configuration.conf.recommends.explorerURL;

//cogumelo.log({ explorerID: explorerID, userID: that.getUserIdentifier(), bounds: bounds });

        if(filters.length == 0) {
          filters = false;
        }

        $.ajax({
          url:resourceRecommenderURL,
          method: 'POST',
          data: { explorerID: explorerID, userID: that.getUserIdentifier(), bounds: bounds, filters: filters },
          success: function( recommendData ) {
            successCallback( recommendData );
          }
        });

      }
    );
  }


});
