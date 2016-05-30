//### Filter Title Model: Used for extracting the title for each correspondent Filter
define([
    'backbone',
    'config/appConfig'
], function (Backbone, Config) {
    var FilterTitleModel = Backbone.Model.extend({
        // Gets the URL from Config and composes it with the selected filter and metric
        urlRoot: Config.URL_STATS_CONFIG,
        url: function () {
            var url = this.urlRoot + "/filter"
            if (!_.isUndefined(this.get('filterID'))) {
                url += "/" + this.get('filterID');
            }
            if (!_.isUndefined(this.get('metricType'))) {
                url += "?metricType=" + this.get('metricType');
            }
            return url;
        },
        defaults: {
            filterID: '',
            metricType: '',
            title: ''
        },
        // Sets the Title field with the correct title
        parse: function (res) {
            this.set({
                title: res.filterTitle
            });
        }
    });
    return FilterTitleModel;
});
