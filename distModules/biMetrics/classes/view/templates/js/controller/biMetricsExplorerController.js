var geozzy = geozzy || {};
if(!geozzy.biMetricsComponents) geozzy.biMetricsComponents={};



geozzy.biMetricsComponents.explorer = geozzy.biMetricsComponents.biMetricsController.extend( {

  biMetricsName: false,

  options: false,
  packageTimestamp: false,

  syncInterval: false,
  metricsUrl: false,

  biApiConf: false,

  metricTemplate: function( metric ) {
    var that = this;


    return {
       "explorer_ID": metric.explorerId,
       "bounds":metric.bounds,
       "zoom":metric.zoom,
       "filters": metric.filters,
       "metricTime": that.getTimesTamp
    };

  },

  getMetricsURL: function() {
    var that = this;
    return that.biApiConf.metrics.explorer;
  }

});
