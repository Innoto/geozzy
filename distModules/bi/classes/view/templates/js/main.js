//### Configuration of the libraries used on Geozzy, with its dependencies
'use strict';

require.config({
    paths: {
        jquery: '/vendor/bower/jquery/dist/jquery.min',
		text: '/vendor/bower/requirejs-text/text',
		underscore: '/vendor/bower/underscore/underscore',
		q: '/vendor/bower/q/q',
		backbone: '/vendor/bower/backbone/backbone',
		mustache: '/vendor/bower/mustache/mustache',
		'highstock': '/vendor/bower/highstock/highstock.src',
		'highstock-exporting': '/vendor/bower/highstock/modules/exporting.src',
		'highstock-export-csv': '/vendor/bower/funplus-highcharts-export-csv/export-csv',
		'moment': '/vendor/bower/moment/min/moment-with-locales.min',
		'datetimepicker': '/vendor/bower/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min',
		'bootstrap-slider': '/vendor/bower/seiyria-bootstrap-slider/dist/bootstrap-slider.min',
		'select2': '/vendor/bower/select2/dist/js/select2.full'
	},
    shim: {
        //* Backbone: depends of Underscore and JQuery libraries. Exports the Backbone one.
        //* Bootstrap: depends of Material and JQuery libraries.
        //* Mustache: It exports the library.
        //* Highstock: depends of JQuery library. Exported as Highstock.
        //* Highstock-exporting: depends of highstock library. Exported as Highstock.
        //* Highstock-export-csv: depends of highstock-exporting library. Exported as Highcharts.
        //* Select2: depends of JQuery library. Exported as Select2.
        //* Moment: is exported as moment.
        //* Datetimepicker: depends of Moment, Bootstrap and JQuery libraries. Exports the DateTimePicker one.
        //* Boostrap-slider: depends of Bootstrap and JQuery libraries. Exports the Slider one.
        //* Q: is exported as q.
        backbone: {
            deps: [
                'underscore'
            ],
            exports: 'Backbone'
        },
        mustache: {
            exports: 'Mustache'
        },
        'highstock': {
            exports: 'Highstock'
        },
        'highstock-exporting': {
            deps: ['highstock'],
            exports: 'Highstock'
        },
        'highstock-export-csv': {
            deps: ['highstock-exporting'],
            exports: 'Highcharts'
        },
        'select2': {
            exports: 'Select2'
        },
        'moment': {
            exports: 'moment'
        },
        'datetimepicker': {
            deps: ['moment'],
            exports: 'DateTimePicker'
        },
        'bootstrap-slider': {
            exports: 'Slider'
        },
        q: {
            exports: 'q'
        }
    }
});

require([
    'jquery',
    'backbone',
    'views/app',
    // Start Backbone history a necessary step for bookmarkable URL's
], function ($,Backbone, AppView) {
    $.noConflict();
    Backbone.history.start();
    new AppView();
});
