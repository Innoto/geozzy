//### Explorers Model: Defines the Metric structure and his basic methods
'use strict';

define([
    'underscore',
    'backbone',
    'config/appConfig'
], function (_, Backbone, Config) {
    // Assign the URL, and contains the explorer elements
    var Explorers = Backbone.Model.extend({
        url: Config.URL_EXPLORERS,
        defaults: {
            elements: []
        },
        // Parsing method where the Keys and his Values are picked from the elements,
        // and pushed into the content list with the correct format (ID and Name)
        //* For each Value, ID and Name are inserted into the explorer List
        parse: function (res) {
            var keys = _.keys(res),
                values = _.values(res);
            var content = [];

            _.each(values, function (value, index) {
                content.push({
                    id: keys[index],
                    name: value.name
                });
            });
            this.set('elements', content)
        }
    });
    return Explorers
});
