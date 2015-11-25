'use strict';

require.config({
	paths: {
		text: '/vendor/bower/requirejs-text/text',
		jquery: '/vendor/bower/jquery/dist/jquery',
		bootstrap: '/vendor/bower/bootstrap/dist/js/bootstrap.min?c',
		underscore: '/vendor/bower/underscore/underscore',
		q: '/vendor/bower/q/q',
		backbone: '/vendor/bower/backbone/backbone',
		material: '/vendor/bower/material-design-lite/material.min',
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
		backbone: {
			deps: [
				'underscore',
				'jquery'
			],
			exports: 'Backbone'
		},
    	mustache: {
        	exports: 'Mustache'
    	},
		'highstock': {
            deps: ['jquery'],
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
		'datetimepicker': {
			deps: ['jquery','moment'],
			exports: 'DateTimePicker'
		},
		'bootstrap-slider': {
            deps: ['jquery'],
            exports: 'Slider'
        },
        q: {
            exports: 'q'
        },
        'select2': {
            deps: ['jquery'],
            exports: 'select2'
        },
	}
});

require([
	'backbone',
    'views/app'
], function (Backbone,AppView) {
	Backbone.history.start();
    new AppView();
});
