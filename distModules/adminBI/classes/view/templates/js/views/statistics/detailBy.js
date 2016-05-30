//### DetailBy View
'use strict';

define([
    'underscore',
    'backbone',
    'mustache',
    'text!templates/statistics/detailBy.html'
], function (_, Backbone, Mustache, DetailByTemplate) {
    // Creating DetailBy template, used for specifying a Client Location
    var DetailByView = Backbone.View.extend({
        events: {
            'change #detailBySelect': 'detailByChanged'
        },
        // Rendering the DetailBy template, and returns that element
        render: function () {
            var rendered = Mustache.render(DetailByTemplate, this.model.toJSON());
            this.$el.html(rendered);
            return this;
        },
        // Manages the Change event, where is taken the correct value ID if is not Undefined
        detailByChanged: function (e) {
            var detailByID = e.target.value;
            if (!_.isUndefined(detailByID)) {
                this.model.set('selectedDetailBy', detailByID);
            }
        }
    });
    return DetailByView;
});