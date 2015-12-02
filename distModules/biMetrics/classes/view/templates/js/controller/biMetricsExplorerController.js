var geozzy = geozzy || {};
if(!geozzy.biMetrics) geozzy.biMetrics={};
if(!geozzy.biMetrics.controller) geozzy.biMetrics.controller={};



geozzy.biMetrics.controller.explorer = geozzy.biMetrics.controller.biMetricsController.extend( {

  metricTemplate: function( metric ) {
    var that = this;
    return {
       "explorer_ID":3,
       "bounds":[
          [
             42.9754,
             -8.36267
          ],
          [
             42.8245,
             -8.63733
          ]
       ],
       "filters":[
         1,32
       ],
       "metricTime": that.packageTimestamp 
    };

  }
});
