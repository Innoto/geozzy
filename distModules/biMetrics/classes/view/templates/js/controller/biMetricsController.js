var geozzy = geozzy || {};
if(!geozzy.biMetricsComponents) geozzy.biMetricsComponents={};




geozzy.biMetricsComponents.biMetricsController = Backbone.Collection.extend({

  initialize: function( options ) {
    var that = this;
    var opts = {
      syncPeriod: 10000 // in miliseconds
    }

    that.options = $.extend(true, {}, opts, options );


    that.packageTimestamp = that.getTimesTamp();

    geozzy.biMetricsInstances.configuration.getConf(
      function() {
        that.biApiConf = geozzy.biMetricsInstances.configuration.conf;
        that.syncEnable();

        // leave page event
        $( window ).unload(function() {
          that.sync();
        });
      }
    );




  },



  packageTemplate: function( ) {

    var that = this;

    var userID = null;

    if( geozzy.userSessionInstance.user) {
      userID = geozzy.userSessionInstance.user.get('id');
    }

    return {
       "user_ID": userID,
       "language": cogumelo.publicConf.C_LANG,
       "session_ID": cogumelo.publicConf.C_SESSION_ID,
       "observationTime": that.packageTimestamp ,
       "device":{
          "type": that.getDevice(),
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
        data: that.packageTemplate() ,
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
