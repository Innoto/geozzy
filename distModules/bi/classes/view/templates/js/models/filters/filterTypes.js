define([
    'underscore',
    'backbone',
    'config/appConfig'
], function(_,Backbone,Config){
    var FilterTypesModel = Backbone.Model.extend({
        urlRoot: Config.URL_STATS_CONFIG,
        url: function(){
            var url = this.urlRoot + '/filters';
            var metricID = this.get('metricID');
            if (!_.isUndefined(metricID)){
                url += '?metricID=' + metricID;
            }
            return url;
        },
        defaults: {
            metricId: '',
            filters: []
        },
        parse: function(res){
            this.set({
                filters: res
            });
        }
    });
    return new FilterTypesModel();
});