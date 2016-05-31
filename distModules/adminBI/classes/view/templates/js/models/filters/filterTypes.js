//### Filter Types Model: Used for getting the correspondent type of filters for a selected Metric
define([
    'underscore',
    'backbone',
    'config/appConfig'
], function (_, Backbone, Config) {
    // Composes the Config URL with Filters and the selected Metric
    var FilterTypesModel = Backbone.Model.extend({
        urlRoot: Config.URL_STATS_CONFIG,
        url: function () {
            var url = this.urlRoot + '/filters';
            var metricID = this.get('metricID');
            if (!_.isUndefined(metricID)) {
                url += '?metricID=' + metricID;
            }
            return url;
        },
        defaults: {
            metricId: '',
            filters: []
        },
        // Sets on Filters field the filters list correspondent to that metric
        parse: function (res) {
            this.set({
                filters: res
            });
        }
    });
    return new FilterTypesModel();
});