define([
    'backbone',
    'config/appConfig'
], function(Backbone,Config){
    var FilterTitleModel = Backbone.Model.extend({
        urlRoot: Config.URL_STATS_CONFIG,
        url: function(){
            var url = this.urlRoot + "/filter"
            if (!_.isUndefined(this.get('filterID'))){
                url += "/" + this.get('filterID');
            }1
            if (!_.isUndefined(this.get('metricType'))){
                url += "?metricType="+this.get('metricType');
            }
            return url;
        },
        defaults: {
            filterID: '',
            metricType: '',
            title: ''
        },
        parse: function(res){
            this.set({
                title: res.filterTitle
            });
        }
    });
    return FilterTitleModel;
});
