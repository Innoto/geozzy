var geozzy = geozzy || {};
if(!geozzy.biMetricsComponents) geozzy.biMetricsComponents={};

$( window ).unload(function() {
  return "Handler for .unload() called.";
});

geozzy.biMetricsComponents.resource = geozzy.biMetricsComponents.biMetricsController.extend( {

  biMetricsName: false,

  options: false,
  packageTimestamp: false,

  syncInterval: false,
  metricsUrl: false,

  biApiConf: false,

  accessedCurrent: false,
  hoverStack: [],
  printedResources:[],
  pendingMetrics: [],

  initialize: function() {
    var that = this;
    that.endPendingEvents();
  },

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
          "term":[0],
          "name":"Recurso " + metric.resourceId,
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


  endPendingEvents: function() {

    var that = this;

    var pendingAccess = Cookies.get("biMetricPendingAccess");

    if( pendingAccess != null ) {

      that.accessedCurrent = JSON.parse(pendingAccess);
      Cookies.remove('biMetricPendingAccess');
      that.eventAccessedEnd();
    }

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

      if( e.resourceId == id && typeof id != 'undefined') {

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
      duration:0,
      resourceId: id,
      section: section,
      event: 'clicked'
    });

    //that.eventAccessed( id, section );
  },



  eventAccessedStart: function( id, section  ) {
    var that = this;

    // random time spent :P

    that.eventAccessedEnd();

    that.accessedCurrent = {
      startTime: that.getTimesTamp(),
      resourceId: id,
      section: section,
    };

    window.onbeforeunload = function() {
      if(that.accessedCurrent != false){
        Cookies.set( "biMetricPendingAccess", that.accessedCurrent );
      }
    };

  },

  eventAccessedEnd: function() {
    var that = this;

    if( that.accessedCurrent != false ){

      var duration = 500;//( that.getTimesTamp() - e.start )/1000;


      that.addMetric({
        duration: duration,
        resourceId: that.accessedCurrent.resourceId,
        section: that.accessedCurrent.section,
        event: 'accessed_total'
      });

      that.accessedCurrent = false;
    }
  },

  eventPrint: function(id, section) {
    var that = this;

    if( $.inArray(id , that.printedResources)  == -1) {

      that.printedResources.push( id );

      that.addMetric({
        duration:0,
        resourceId: id,
        section: section,
        event: 'printed'
      });
    }
  },

  eventCommented: function(id, section) {
    var that = this;

    that.addMetric({
      duration: 0,
      resourceId: id,
      section: section,
      event: 'commented'
    });
  },

  eventShared: function(id, section) {
    var that = this;

    that.addMetric({
      duration: 0,
      resourceId: id,
      section: section,
      event: 'shared'
    });
  },

  eventFavourited: function(id, section) {
    var that = this;

    that.addMetric({
      duration: 0,
      resourceId: id,
      section: section,
      event: 'favourited'
    });
  }

});
