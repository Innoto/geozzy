'use strict';

require.config({
	paths: {
		text: '/vendor/bower/requirejs-text/text',
		jquery: '/vendor/bower/jquery/dist/jquery',
		underscore: '/vendor/bower/underscore/underscore',
		backbone: '/vendor/bower/backbone/backbone',
		bootstrap :  "/vendor/bower/bootstrap/dist/js/bootstrap.min",
		material: '/vendor/bower/material-design-lite/material.min',
		mustache: '/vendor/bower/mustache/mustache',
		'highcharts-release': '/vendor/bower/highcharts-release/highcharts',
		'highcharts-exporting': '/vendor/bower/highcharts-release/modules/exporting',
		'higcharts-export-csv': '/vendor/bower/funplus-highcharts-export-csv/export-csv',
		'leaflet': '/vendor/bower/leaflet/dist/leaflet',
		'heatmap': '/vendor/bower/heatmap.js-amd/build/heatmap',
		'leaflet-heatmap': '/vendor/bower/heatmap.js-amd/plugins/leaflet-heatmap',
		'select2': '/vendor/bower/select2/dist/js/select2.min',
		'moment': '/vendor/bower/moment/min/moment-with-locales.min',
		'datetimepicker': '/vendor/bower/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min'
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
		'highcharts-release': {
			deps: ['jquery'],
			exports: 'Highcharts'
		},
		'highcharts-exporting': {
			deps: ['highcharts-release'],
			exports: 'Highcharts'
		},
		'higcharts-export-csv': {
			deps: ['highcharts-exporting'],
			exports: 'Highcharts'
		},
		'leaflet': {
			exports: 'L'
		},
		'heatmap': {
			exports: 'Heatmap'
		},
		'leaflet-heatmap': {
			deps: ['heatmap','leaflet'],
			exports: 'HeatmapOverly'
		},
		'select2': {
			deps: ['jquery'],
			exports: 'Select2'
		},
		'datetimepicker': {
			deps: ['jquery','moment','bootstrap'],
			exports: 'DateTimePicker'
		}
	}
});

require([
	'backbone',
    'routers/router',
    'views/app',
    'bootstrap',
    'material'
], function (Backbone,Router,AppView) {
    new Router();
	Backbone.history.start();
    new AppView();
});
