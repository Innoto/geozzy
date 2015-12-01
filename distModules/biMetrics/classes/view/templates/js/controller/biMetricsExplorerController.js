var geozzy = geozzy || {};
if(!geozzy.biMetrics) geozzy.biMetrics={};
if(!geozzy.biMetrics.conroller) geozzy.biMetrics.controller={};

geozzy.biMetrics.controller.explorer = geozzy.biMetrics.controller.biMetricsController.extend( {



  metricTemplate: function() {
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

       ],
       "metricTime":"Mon Nov 16 2015 11:33:16 GMT+0200 (Hora de verano romance)"
    };

  }
});
