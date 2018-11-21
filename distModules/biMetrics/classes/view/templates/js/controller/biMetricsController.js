var geozzy = geozzy || {};
if(!geozzy.biMetricsComponents) geozzy.biMetricsComponents={};


geozzy.biMetricsComponents.biMetricsController = Backbone.Collection.extend({

  //resourcePendingMetrics: [],
  initialize: function( options ) {
    var that = this;

    that.pendingMetrics = [];

    var opts = {
      syncPeriod: 5000 // in miliseconds
    }

    that.options = $.extend(true, {}, opts, options );


    that.packageTimestamp = that.getTimesTamp();

    geozzy.biMetricsInstances.configuration.getConf(
      function() {
        that.biApiConf = geozzy.biMetricsInstances.configuration.conf;
        that.syncEnable();
      }
    );


    if( typeof that.endPendingEvents != 'undefined') {
      that.endPendingEvents();
    }



  },



  packageTemplate: function( ) {

    var that = this;



    var metricsObj = {
       "language": cogumelo.publicConf.C_LANG,
       "session_ID": cogumelo.publicConf.C_SESSION_ID,
       "observationTime": that.packageTimestamp ,
       "device":{
          "type": that.getDevice(),
          "device_ID":0
       },
       "metrics": that.pendingMetrics
    }


    if( geozzy.userSessionInstance.user && geozzy.userSessionInstance.user.get('id')) {
      metricsObj.user_ID = geozzy.userSessionInstance.user.get('id');
    }

    return metricsObj;
  },

  metricTemplate: function() {
    cogumelo.log('biMetrics: template Metric must be declared in "metricTemplate" Method');

    return false;
  },

  getMetricsURL: function() {
    cogumelo.log('biMetrics: method "getMetricsURL" not defined');
  },

  addMetric: function( data ) {
    var that = this;

    var metric = false;

    if( metric = that.metricTemplate( data ) ) {

      that.pendingMetrics.push(metric);
    }

  },

  reset: function() {
    var that = this;

    that.pendingMetrics = [];
    that.packageTimestamp = false;

  },


  sync: function() {
    var that = this;

    if( that.pendingMetrics.length > 0 ) {

      if( that.getMetricsURL() != '' ) {
        $.ajax({
          type: "POST",
          url: that.getMetricsURL(),
          data: that.packageTemplate() ,
          dataType: 'application/json'
        });
      }
      else {
        cogumelo.log( 'BI METRICS:', that.packageTemplate());
      }

      that.reset();
      that.packageTimestamp = that.getTimesTamp();
    }

  },

  syncDisable: function() {
    var that = this;

    if( that.syncInterval != false ) {
      clearInterval(  that.syncInterval );
    }
    else {
      cogumelo.log('biMetrics: that.syncInterval already defined');
    }

  },




  syncEnable: function() {

    var that = this;

    if( that.syncInterval == false ) {

      that.syncInterval = setInterval( function(){
        // syncrhonization
        that.sync();
      }, that.options.syncPeriod);
    }
    else {
      cogumelo.log('biMetrics: that.syncInterval already defined');
    }


  },

  getTimesTamp: function() {
    var that = this;

    return Math.floor( Date.now() );  // timestamp miliseconds
  },

  getDevice: function() {
    var that = this;
    var d = 'desk';


    if( $('html').hasClass('tablet') ) {
      d = 'tablet';
    }
    else
    if(  $('html').hasClass('mobile')  ) {
      d = 'mob';
    }

    return d;
  }



});
