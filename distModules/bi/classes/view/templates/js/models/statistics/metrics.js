'use strict';

define([
    'underscore',
    'backbone',
    'config/appConfig'
],function(_,Backbone,Config){

    var MetricsModel = Backbone.Model.extend({
        urlRoot: Config.URL_STATS_CONFIG,
        url: function(){
            return this.urlRoot + '/metrics';
        },
        defaults: {
            metrics: [],
            selectedMetric: ''
        },
        getSelectedMetricType : function(){
            var metricID = this.get('selectedMetric');
            return _.findWhere(this.metrics,{metricID:metricID});
        },
        parse: function(res){
            this.set({
                metrics: res
            });
        }
    });
    return new MetricsModel();
});
