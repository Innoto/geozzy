var geozzy = geozzy || {};
if(!geozzy.biMetrics) geozzy.biMetrics={};
if(!geozzy.biMetrics.controller) geozzy.biMetrics.controller={};



geozzy.biMetrics.controller.biMetricsController = Backbone.Collection.extend({


  options: false,
  packageTimestamp: false,
  pendingMetrics: [],
  syncInterval: false,
  metricsUrl: false,

  biApiConf: false,


  initialize: function( options ) {
    var that = this;
    var opts = {
      syncPeriod: 10000 // in miliseconds
    }

    that.options = $.extend(true, opts, options );


    that.packageTimestamp = that.getTimesTamp();

    $.ajax({
      url:'/api/core/bi',
      cache: true,
      success: function( dat ){
        that.biApiConf = dat;
        that.syncEnable();

        // leave page event
        $( window ).unload(function() {
          that.sync();
        });

      }
    });






  },

  packageTemplate: function( ) {

    var that = this;

    return {
       "user_ID":283,
       "language": GLOBAL_C_LANG,
       "session_ID":"0Eca798C0EfD46CA3de2827B8ed6DA",
       "observationTime": that.packageTimestamp ,
       "device":{
          "type":"mob",
          "device_ID":0
       },
       "metrics": that.pendingMetrics

    };
  },

  metricTemplate: function() {
    console.log('biMetrics: template Metric must be declared in "metricTemplate" Method');

    return false;
  },

  getMetricsURL: function() {
    console.log('biMetrics: method "getMetricsURL" not defined');
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

    this.pendingMetrics = [];
    this.packageTimestamp = false;

  },


  sync: function() {
    var that = this;


    if( that.pendingMetrics.length > 0 ) {

      $.ajax({
        type: "POST",
        url: that.getMetricsURL(),
        data: JSON.stringify( that.packageTemplate() ),
        dataType: 'application/json'
      });

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
      console.log('biMetrics: that.syncInterval already defined');
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
      console.log('biMetrics: that.syncInterval already defined');
    }


  },

  getTimesTamp: function() {
    var that = this;

    var data = new Date();

    return Date.UTC(
      data.getUTCFullYear(),
      data.getUTCMonth(),
      data.getUTCDate() ,
      data.getUTCHours(),
      data.getUTCMinutes(),
      data.getUTCSeconds(),
      data.getUTCMilliseconds()
    )

  }

});
