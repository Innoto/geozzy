var geozzy = geozzy || {};
if(!geozzy.biMetricsComponents) geozzy.biMetricsComponents={};



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
/*
  initialize: function() {
    var that = this;

    //this.resourcePendingMetrics = [];

    that.endPendingEvents();

  },
*/
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
      "metricTime": that.getTimesTamp(),
      "resource": metric.resourceId,
      "event": metric.event,
      "seconds": metric.duration,
      "section": metric.section
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

    that.eventAccessedEnd();

    that.accessedCurrent = {
      startTime: that.getTimesTamp(),
      endTime: false,
      resourceId: id,
      section: section
    };


    //cogumelo.log('ENGADE', that.accessedCurrent );

    window.onbeforeunload = function() {
      if(that.accessedCurrent != false){
        that.accessedCurrent.endTime = that.getTimesTamp();
        Cookies.set( "biMetricPendingAccess", that.accessedCurrent );
      }
    };

  },

  eventAccessedEnd: function( ) {
    var that = this;


    if( that.accessedCurrent != false ){



      if( that.accessedCurrent.endTime != false ) {
        var endTime = that.accessedCurrent.endTime;
      }
      else {
        var endTime = that.getTimesTamp();
      }

      var duration = ( endTime - that.accessedCurrent.startTime )/1000;

      that.addMetric({
        duration: duration,
        resourceId: that.accessedCurrent.resourceId,
        section: that.accessedCurrent.section,
        event: 'accessed_total'
      });


      //cogumelo.log(that.resourcePendingMetrics)

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
