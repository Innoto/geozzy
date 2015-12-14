var geozzy = geozzy || {};
if(!geozzy.biMetrics) geozzy.biMetrics={};
if(!geozzy.biMetrics.controller) geozzy.biMetrics.controller={};



geozzy.biMetrics.controller.resource = geozzy.biMetrics.controller.biMetricsController.extend( {

  hoverTimes: {},

  metricTemplate: function( metric ) {
    var that = this;
    /*
    return {
       "duration":0,
       "section":  metric.section,
       "resource": metric.resourceId,
       "event": "hover",
       "metricTime": that.getTimesTamp()
    };*/

    return {
       "seconds": 1.0,
       "section": metric.section,
       "resource":{
          "term":[
             34,
             26,
             30,
             25,
             33,
             32
          ],
          "name":"Recurso 1793",
          "resource_ID": metric.resourceId,
          "topic":[
             1
          ],
          "location":[
             42.8603,
             42.8603
          ],
          "type":{
             "name":"Tipo 5",
             "type_ID":5
          }
       },
       "event":{
          "event_ID":0,
          "name": metric.event,
       },
       "metricTime": that.getTimesTamp()
    };

  },

  getMetricsURL: function() {
    var that = this;
    return that.biApiConf.metrics.resource;
  },



  eventHoverStart: function( id ) {
console.log(id);
  },

  eventHoverEnd: function( id ) {

  },

  eventClick: function( id ) {

  }

});
