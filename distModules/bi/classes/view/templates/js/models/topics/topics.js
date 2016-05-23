//### Topics Model
'use strict';

define([
    'underscore',
    'backbone',
    'config/appConfig'
], function (_, Backbone, Config) {
    // Model with the correspondent URL and the elements contained
    var Topics = Backbone.Model.extend({
        url: Config.URL_TOPICS,
        defaults: {
            elements: []
        },
        // Push the different values into elements
        parse: function (res) {
            var content = [];
            _.each(res, function (value) {
                content.push({
                    id: value.id,
                    name: value.name
                });
            });
            this.set('elements', content);
        }
    });
    return Topics;
});
