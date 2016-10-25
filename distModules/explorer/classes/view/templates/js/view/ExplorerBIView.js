var geozzy = geozzy || {};
if(!geozzy.explorerComponents) geozzy.explorerComponents={};

geozzy.explorerComponents.biView = Backbone.View.extend({

  displayType: 'plugin',
  parentExplorer: false,

  initialize: function( opts ) {
    var that = this;
    var options = new Object({
      metricsExplorerController: geozzy.biMetricsInstances.explorer,
      metricsResourceController: geozzy.biMetricsInstances.resource
    });

    that.options = $.extend(true, {}, options, opts);


  },

  setParentExplorer: function( parentExplorer ) {
    var  that = this;

    that.parentExplorer = parentExplorer;

    that.parentExplorer.bindEvent('contextChange', function( metricData ){
      that.options.metricsExplorerController.addMetric(metricData);
    });

    that.parentExplorer.bindEvent('resourceQuit', function( ){
      that.options.metricsResourceController.eventAccessedEnd();
    });

    that.parentExplorer.bindEvent('resourceClick', function( metric ){
      that.options.metricsResourceController.eventClick( metric.id, metric.section );
    });

    that.parentExplorer.bindEvent('resourcePrint', function( metric ){
      that.options.metricsResourceController.eventPrint( metric.id, metric.section );
    });

    that.parentExplorer.bindEvent('resourcePrint', function( metric ){
      that.options.metricsResourceController.eventPrint( metric.id, metric.section );
    });

    that.parentExplorer.bindEvent('resourceHover', function( metric ){
      that.options.metricsResourceController.eventHoverStart( metric.id, metric.section );
    });

    that.parentExplorer.bindEvent('resourceMouseOut', function( metric ){
      that.options.metricsResourceController.eventHoverEnd( metric.id );
    });

  },

  render: function( ) {
    var that = this;
  }

});
