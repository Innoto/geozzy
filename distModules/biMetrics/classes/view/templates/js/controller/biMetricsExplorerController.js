var geozzy = geozzy || {};
if(!geozzy.biMetrics) geozzy.biMetrics={};
if(!geozzy.biMetrics.controller) geozzy.biMetrics.controller={};



geozzy.biMetrics.controller.explorer = geozzy.biMetrics.controller.biMetricsController.extend( {

  biMetricsName: false,

  options: false,
  packageTimestamp: false,

  syncInterval: false,
  metricsUrl: false,

  biApiConf: false,

  pendingMetrics: [],
  metricTemplate: function( metric ) {
    var that = this;


    return {
       "explorer_ID": metric.explorerId,
       "bounds":metric.bounds,
       "filters": metric.filters,
       "metricTime": that.getTimesTamp
    };

  },

  getMetricsURL: function() {
    var that = this;
    return that.biApiConf.metrics.explorer;
  }

});
