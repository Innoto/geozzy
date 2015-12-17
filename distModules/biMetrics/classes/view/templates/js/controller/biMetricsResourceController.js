var geozzy = geozzy || {};
if(!geozzy.biMetrics) geozzy.biMetrics={};
if(!geozzy.biMetrics.controller) geozzy.biMetrics.controller={};



geozzy.biMetrics.controller.resource = geozzy.biMetrics.controller.biMetricsController.extend( {

  hoverStack: [],
  printedResources:[],

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
       "seconds": metric.duration,
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



  eventHoverStart: function( id, section ) {
    var that = this;

    that.hoverStack.push({
          start: that.getTimesTamp(),
          resourceId: id,
          section: section,
          event: 'hover'
    });
  },

  eventHoverEnd: function( id ) {
    var that = this;

    $.each( that.hoverStack, function( i, e ){

      if( e.resourceId == id ) {

        that.hoverStack[i] = false;

        e.duration= ( that.getTimesTamp() - e.start )/1000;
        e.start = null;

        that.addMetric( e );

      }
    });


  },

  eventClick: function( id, section ) {
    var that = this;

    that.addMetric({
          resourceId: id,
          section: section,
          event: 'clicked'
    });
  },

  eventPrint: function(id, section) {
    var that = this;

    if( $.inArray(id , that.printedResources)  == -1) {
      that.printedResources.push( id );

      that.addMetric({
            resourceId: id,
            section: section,
            event: 'printed'
      });
    }


  }

});
