'use strict';

define([
    'underscore',
    'backbone',
    'config/appConfig'
],function(_,Backbone,Config){

    var DetailByModel = Backbone.Model.extend({
        urlRoot: Config.URL_STATS_CONFIG,
        url: function(){
            return this.urlRoot + "/countries"
        },
        defaults: {
            countryIDs: [],
            countries: [],
            selectedDetailBy: ''
        },
        requestCountries: function(opts){
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
