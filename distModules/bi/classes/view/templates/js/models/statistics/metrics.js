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
            filterMetrics: [],
            selectedMetric: ''
        },
        getMetricType : function(metricID){
            var metric = _.findWhere(this.get('metrics'),{metricID:metricID});
            if (!_.isUndefined(metric) && !_.isUndefined(metric.type)){
                return metric.type;
            }else{
                return undefined;
            }
        },
        parse: function(res){
            this.set({
                metrics: res
            });
        }
    });
    return new MetricsModel();
});
