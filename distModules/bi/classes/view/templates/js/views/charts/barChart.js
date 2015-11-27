//### Bar Chart View
'use strict';

define([
    'underscore',
    'backbone',
    'highstock-export-csv'
], function (_, Backbone) {
    // Creating BarChart template, used for charting a determined quantity of some stats
    var BarChartView = Backbone.View.extend({
        el: '#result',
        // Initializes the chart element
        initialize: function (options) {

            if (_.size(this.$('#chart')) === 0) {
                this.$el.append('<div id=\'chart\'></div>');
            }
            this.$chart = this.$('#chart');

            this.groups = options.groups;
            this.data = options.data;
        },
        // Renders the chart using Highstock
        render: function () {
            var scrollEnabled = (this.groups.length > 1);
            this.$chart =
                this.$('#chart').highcharts({
                    credits: {
                        enabled: false
                    },
                    //* Bar Chart Type used, Zoom used only for the X axis and Reset Zoom position at the bottom/right of the chart
                    chart: {
                        type: 'bar',
                        zoomType: 'x',
                        panning: true,
                        resetZoomButton: {
                            position: {
                                align: 'right',
                                verticalAlign: 'bottom',
                                x: 0,
                                y: 20
                            },
                            relativeTo: 'bar'
                        }
                    },
                    scrollbar: {
                        enabled: scrollEnabled
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
                    //* Callback function to format the text of the tooltip
                    tooltip: {
                        formatter: function () {
                            return '<strong>' + this.x + '</strong><br/>' + this.y;
                        }
                    },
                    //* Predefined configuration for the plotting. Whether to stack the values of each series on top of each other
                    plotOptions: {
                        series: {
                            stacking: 'normal'
                        }
                    },
                    series: [{
                        name: 'Datos Estadisticos',
                        data: this.data
                    }],
                    //* Function added for exporting the CSV file type
                    exporting: {
                        csv: {
                            itemDelimiter: ";"
                        }
                    }
                });
            // If data size is higher than 10, then it shows only the top 10 of the charts, with the possibility of resetting this zoom
            var size = _.size(this.data);
            if (size > 10) {
                this.$chart.highcharts().xAxis[0].setExtremes(0, 9);
                this.$chart.highcharts().showResetZoom();
            }
        },
        remove: function () {
            this.$chart.remove();
        }
    });
    return BarChartView;
});

