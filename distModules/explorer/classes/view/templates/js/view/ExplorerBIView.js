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

    that.parentExplorer.bindEvent('context_change', function( metricData ){
      that.options.metricsExplorerController.addMetric(metricData);
    });

    that.parentExplorer.bindEvent('resource_quit', function( ){
      that.options.metricsResourceController.eventAccessedEnd();
    });

/*


    that.parentExplorer.bindEvent('resourceHover', function( params ){
      that.show(params.id);
    });

    that.parentExplorer.bindEvent('resourceMouseOut', function( params ){
      that.hide(params.id);
    });


    that.parentExplorer.bindEvent('resourceClick', function( params ){
      //that.show(params.id);
    });
*/
  },

  render: function( ) {
    var that = this;
  }

});
