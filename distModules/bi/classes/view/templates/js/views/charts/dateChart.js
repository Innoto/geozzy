'use strict';

define([
    'jquery',
    'underscore',
    'backbone',
    'higcharts-export-csv'
], function($,_,Backbone,Highcharts){
    var DateChartView = Backbone.View.extend({
        el: '#result',
        initialize: function(options){
            if (_.size(this.$('#chart')) === 0){
                this.$el.append('<div id=\'chart\'></div>');
            }
            this.$chart = this.$('#chart');

            var data = [];
            _.each(options.groups, function(elementGroup, index){
                var date = elementGroup;
                var dateUTC = Date.UTC(date.year,date.month-1,date.dayOfMonth,date.hour);
                var elementData = options.data[index];
                data.push([dateUTC,elementData]);
            });
            this.data = data;
        },
        render: function(){
            this.chart =
                this.$chart.highcharts({
                    credits: {
                        enabled: false
                    },
                    chart: {
                        zoomType: 'x'
                    },
                    title: {
                        text: ''
                    },
                    xAxis: {
                        type: 'datetime'
                    },
                    yAxis: {
                        title: ''
                    },
                    legend: {
                        enabled: false
                    },
                    plotOptions: {
                        area: {
                            fillColor: {
                                linearGradient: {
                                    x1: 0,
                                    y1: 0,
                                    x2: 0,
                                    y2: 1
                                },
                                stops: [
                                    [0, "#7cb5ec"],
                                    [1, "rgba(124,181,236,0)"]
                                ]
                            },
                            marker: {
                                radius: 2
                            },
                            lineWidth: 1,
                            states: {
                                hover: {
                                    lineWidth: 1
                                }
                            },
                            threshold: null
                        }
                    },
                    series: [{
                        name: 'Datos Estadisticos',
                        type: 'area',
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
    return DateChartView;
});
