//### Bar Chart View
'use strict';

define([
    'jquery',
    'underscore',
    'backbone',
    'highstock-export-csv'
], function ($, _, Backbone) {
    // Creating BarChart template, used for charting a determined quantity of some stats
    var BarChartView = Backbone.View.extend({
        el: '#result',
        // Initializes the chart element
        initialize: function (options) {
            // Creates the chart
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
                    //* No credits function used
                    credits: {
                        enabled: false
                    },
                    //* Bar Chart Type used, Zoom used only for the X axis, and Panning function enabled
                    chart: {
                        type: 'bar',
                        zoomType: 'x',
                        panning: true
                    },
                    //* Scroll bar enabled
                    scrollbar: {
                        enabled: scrollEnabled
                    },
                    //* Empty title for the chart
                    title: {
                        text: ''
                    },
                    //* Sets the categories in groups for the xAxis
                    xAxis: {
                        categories: this.groups
                    },
                    //* Nothing used for list the yAxis
                    yAxis: {
                        title: '',
                        min: 0
                    },
                    //* No legend used
                    legend: {
                        enabled: false
                    },
                    //* Callback function to format the text of the tooltip
                    tooltip: {
                        formatter: function () {
                            return '<strong>' + this.x + '</strong><br/>' + this.y;
                        }
                    },
                    //* Predefined configuration for the plotting
                    plotOptions: {
                        //* Whether to stack the values of each series on top of each other
                        series: {
                            stacking: 'normal'
                        }
                    },
                    //* Introduces the data to show
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
        // Removes the chart
        remove: function () {
            this.$chart.remove();
        }
    });
    return BarChartView;
});

