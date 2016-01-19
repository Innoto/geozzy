var geozzy = geozzy || {};
if(!geozzy.biMetrics) geozzy.biMetrics={};
if(!geozzy.biMetrics.controller) geozzy.biMetrics.controller={};



geozzy.biMetrics.controller.explorer = geozzy.biMetrics.controller.biMetricsController.extend( {

  metricTemplate: function( metric ) {
    var that = this;
    console.log({
       "explorer_ID":3,
       "bounds":metric.bounds,
       "filters": metric.filters,
       "metricTime": that.getTimesTamp()
    })

    return {
       "explorer_ID":3,
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
