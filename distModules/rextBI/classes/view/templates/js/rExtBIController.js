var geozzy = geozzy || {};




geozzy.rExtBIController = function( opts ) {
  var that = this;


  that.initialize = function() {
    //alert( geozzy.rExtBIOptions.resourceID );

    geozzy.biMetricsInstances.resource.eventAccessedStart( geozzy.rExtBIOptions.resourceID, 'Resource page' );

  };

};
