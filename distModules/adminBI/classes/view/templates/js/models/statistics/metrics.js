//### Metrics Model: Defines the Metric structure and his basic methods
'use strict';

define([
    'underscore',
    'backbone',
    'config/appConfig'
], function (_, Backbone, Config) {
    // Get and assign the URL, and contains the whole metrics, the selected one, and their filters
    var MetricsModel = Backbone.Model.extend({
        urlRoot: Config.URL_STATS_CONFIG,
        url: function () {
            return this.urlRoot + '/metrics';
        },
        defaults: {
            metrics: [],
            filterMetrics: [],
            selectedMetric: ''
        },
        // Find the Metric Type: if resources or explorers, which affects to the organizations and filters
        getMetricType: function (metricID) {
            var metric = _.findWhere(this.get('metrics'), {metricID: metricID});
            if (!_.isUndefined(metric) && !_.isUndefined(metric.type)) {
                return metric.type;
            } else {
                return undefined;
            }
        },
        // Set into metrics field the sent ones
        parse: function (res) {
            this.set({
                metrics: res
            });
        }
    });
    return new MetricsModel();
});
