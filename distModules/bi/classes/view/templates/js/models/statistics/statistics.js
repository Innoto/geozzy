'use strict';

define([
    'underscore',
    'backbone',
    'config/appConfig'
],function(_,Backbone,Config){

    var StatsModel = Backbone.Model.extend({
        urlRoot: Config.URL_STATS,
        url: function() {
            return this.urlRoot;
        },
        defaults: {
            metricID: '',
            groupByID: ''
        },
        requestStats: function(opts) {
            var url = this.url(),
                options = {
                    url: url,
                    type: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify(this.attributes)
                };

            _.extend(options, opts);

            return (this.sync || Backbone.sync).call(this, null, this, options);
        }
    });
    return new StatsModel();
});
