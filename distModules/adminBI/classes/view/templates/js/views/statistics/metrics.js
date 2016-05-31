//### Metrics View

define([
    'underscore',
    'backbone',
    'mustache',
    'text!templates/statistics/metrics.html'
], function (_, Backbone, Mustache, MetricsTemplate) {
    // Creating Metrics template, used for selecting a Metric
    var MetricsView = Backbone.View.extend({
        events: {
            'change #metrics': 'changeMetric'
        },
        defaults: {
            selectedMetric: '',
            selectedFilterID: ''
        },
        // Rendering the Metrics template, and returns that element
        render: function () {
            var rendered = Mustache.render(MetricsTemplate, this.model.toJSON());
            this.$el.html(rendered);
            return this;
        },
        // Manages the Change event, where is taken the correct value ID if Metric is not Undefined
        changeMetric: function (e) {
            var metricID = e.target.value,
                filterID = $('option:selected', $(e.target)).attr('filterID');
            if (!_.isUndefined(metricID)) {
                this.model.set('selectedMetric', metricID);
            }
            this.model.set('selectedFilterID', filterID);
        }
    });
    return MetricsView;
});
