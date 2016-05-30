//### Organizations Model: Defines the Organization structure and his setting method
'use strict';

define([
    'backbone',
    'config/appConfig'
], function (Backbone, Config) {
    // Get and assign the URL, and contains the whole organizations, the selected one, and the correspondent Metric ID
    var OrgsModel = Backbone.Model.extend({
        urlRoot: Config.URL_STATS_CONFIG,
        // Parses the URL with the predefined one, plus the Organizations field and the Metric ID
        url: function () {
            var url = this.urlRoot + '/organizations';
            var metricID = this.get('metricID');
            if (metricID) {
                url += '?metricID=' + metricID;
            }
            return url;
        },
        defaults: {
            organizations: [],
            selectedOrg: '',
            metricID: ''
        },
        // Set into organizations field the sent ones
        parse: function (res) {
            this.set({
                organizations: res
            });
        }
    });
    return new OrgsModel();
});