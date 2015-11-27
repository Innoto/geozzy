//### DetailBy Model: Defines the Detail By structure and the Request Countries method
'use strict';

define([
    'underscore',
    'backbone',
    'config/appConfig'
], function (_, Backbone, Config) {
    // Get and assign the URL, and contains the whole Countries, the Countries ID, and the selected one
    var DetailByModel = Backbone.Model.extend({
        urlRoot: Config.URL_STATS_CONFIG,
        url: function () {
            return this.urlRoot + "/countries"
        },
        defaults: {
            countryIDs: [],
            countries: [],
            selectedDetailBy: ''
        },
        // Return the Countries Request after setting the Structure for it
        requestCountries: function (opts) {
            var url = this.url(),
                options = {
                    url: url,
                    type: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(this.get('countryIDs'))
                };
            _.extend(options, opts);
            return (this.sync || Backbone.sync).call(this, null, this, options);
        }
    });
    return new DetailByModel();
});
