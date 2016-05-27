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

    if( geozzy.userSessionInstance.user) {
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

        //console.log(resourceRecommenderURL);
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

  explorer: function( explorerID, bounds, successCallback ) {
    var that = this;
    geozzy.biMetricsInstances.configuration.getConf(
      function() {
        var resourceRecommenderURL = geozzy.biMetricsInstances.configuration.conf.recommends.explorerURL;

//console.log({ explorerID: explorerID, userID: that.getUserIdentifier(), bounds: bounds });

        $.ajax({
          url:resourceRecommenderURL,
          method: 'POST',
          data: { explorerID: explorerID, userID: that.getUserIdentifier(), bounds: bounds },
          success: function( recommendData ) {
            successCallback( recommendData );
          }
        });

      }
    );
  }


});
