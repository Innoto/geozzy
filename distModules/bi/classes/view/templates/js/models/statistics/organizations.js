'use strict';

define([
    'backbone',
    'config/appConfig'
],function(Backbone,Config){

    var OrgsModel = Backbone.Model.extend({
        urlRoot: Config.URL_STATS_CONFIG,
        url: function(){
            var url = this.urlRoot + '/organizations';
            var metricID = this.get('metricID');
            if (metricID){
                url += '?metricID=' + metricID;
            }
            return url;
        },
        defaults: {
            organizations: [],
            selectedOrg: '',
            metricID: ''
        },
        parse: function(res){
            this.set({
                organizations: res
            });
        }
    });
    return new OrgsModel();
});