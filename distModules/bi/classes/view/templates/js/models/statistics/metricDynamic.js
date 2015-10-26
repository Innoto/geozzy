'use strict';

define([
    'underscore',
    'backbone',
    'config/appConfig'
], function (_, Backbone, Config) {

    var MetricsDynamicModel = Backbone.Model.extend({
        url: Config.URL_INNOTO_DYNAMIC_METRICS,
        defaults: {
            metrics: []
        },
        parse: function (res) {
            var content = [];
            _.each(res, function (resource) {
                var r = _.pick(resource, 'id', 'name_es');
                content.push({
                    metrics: r.id,
                    metricsName: r.name_es
                });
            });
            console.log(content);
        }
    });
    return MetricsDynamicModel;
});