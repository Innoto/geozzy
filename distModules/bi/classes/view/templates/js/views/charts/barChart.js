'use strict';

define([
    'jquery',
    'underscore',
    'backbone',
    'higcharts-export-csv'
], function($,_,Backbone,Highcharts){
    var BarChartView = Backbone.View.extend({
        el: '#result',
        initialize: function(options){
            if (_.size(this.$('#chart')) === 0){
                this.$el.append('<div id=\'chart\'></div>');
            }
            this.$chart = this.$('#chart');

            this.groups = options.groups;
            this.data = options.data;
        },
        render: function(){
            this.chart =
                this.$chart.highcharts({
                    credits: {
                        enabled: false
                    },
                    chart: {
                        type: 'bar'
                    },
                    title: {
                        text: ''
                    },
                    xAxis: {
                        categories: this.groups
                    },
                    yAxis: {
                        title: '',
                        min: 0
                    },
                    legend: {
                        enabled: false
                    },
                    tooltip: {
                        formatter: function(){
                            return '<strong>' + this.x + '</strong><br/>' + this.y;
                        }
                    },
                    plotOptions: {
                        series: {
                            stacking: 'normal'
                        }
                    },
                    series: [{
                        name: 'Datos Estadisticos',
                        data: this.data
                    }],
                    exporting: {
                        csv: {
                            itemDelimiter: ";"
                        }
                    }
                });
        },
        remove: function(){
            this.$chart.remove();
        }
    });
    return BarChartView;
});

