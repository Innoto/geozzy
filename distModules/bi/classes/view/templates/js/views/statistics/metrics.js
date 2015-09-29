'use strict';

define([
    'jquery',
    'underscore',
    'backbone',
    'mustache',
    'text!templates/statistics/metrics.html'
],function($,_,Backbone,Mustache,MetricsTemplate){

    var MetricsView = Backbone.View.extend({
        events: {
            'change #metrics': 'changeMetric'
        },
        render: function (){
            var rendered = Mustache.render(MetricsTemplate,this.model.toJSON());
            this.$el.html(rendered);
            return this;
        },
        changeMetric: function(e){
            var metricID = e.target.value;
            if (!_.isUndefined(metricID)){
                this.model.set('selectedMetric',metricID);
            }
        }
    });
    return MetricsView;
});
