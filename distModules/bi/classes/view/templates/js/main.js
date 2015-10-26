'use strict';

require.config({
	paths: {
		text: '/vendor/bower/requirejs-text/text',
		jquery: '/vendor/bower/jquery/dist/jquery',
		underscore: '/vendor/bower/underscore/underscore',
		q: '/vendor/bower/q/q',
		backbone: '/vendor/bower/backbone/backbone',
		bootstrap :  "/vendor/bower/bootstrap/dist/js/bootstrap.min",
		material: '/vendor/bower/material-design-lite/material.min',
		mustache: '/vendor/bower/mustache/mustache',
		'highstock': '/vendor/bower/highstock/highstock.src',
		'highstock-exporting': '/vendor/bower/highstock/modules/exporting.src',
		'higcharts-export-csv': '/vendor/bower/funplus-highcharts-export-csv/export-csv',
		'select2': '/vendor/bower/select2/dist/js/select2.min',
		'moment': '/vendor/bower/moment/min/moment-with-locales.min',
		'datetimepicker': '/vendor/bower/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min',
		'bootstrap-slider': '/vendor/bower/seiyria-bootstrap-slider/dist/bootstrap-slider.min'
	},
	shim: {
		backbone: {
			deps: [
				'underscore',
				'jquery'
			],
			exports: 'Backbone'
		},
		bootstrap: {
			deps: [
				'jquery',
                'material'
			]
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
		'select2': {
			deps: ['jquery'],
			exports: 'Select2'
		},
		'datetimepicker': {
			deps: ['jquery','moment','bootstrap'],
			exports: 'DateTimePicker'
		},
		'bootstrap-slider': {
            deps: ['jquery', 'bootstrap'],
            exports: 'Slider'
        },
        q: {
            exports: 'q'
        }
	}
});

require([
	'backbone',
    'views/app',
    'bootstrap',
    'material'
], function (Backbone,AppView) {
	Backbone.history.start();
    new AppView();
});
