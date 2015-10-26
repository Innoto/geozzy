//### Date Filter View
define([
    'jquery',
    'backbone',
    'mustache',
    'text!templates/filters/dateFilter.html',
    'collections/filters/filters',
    'datetimepicker',
    'moment'
], function ($, Backbone, Mustache, filtersTemplate, FiltersCollection, DateTimePicker, moment) {
    // Creating DateFilter template, used for filtering by Date
    var DateFilter = Backbone.View.extend({
        // Default value for tagName
        tagName: 'div',
        // Picking the Closing event on the filter bar
        events: {
            'click #close': 'remove'
        },
        // Rendering the DateFilter template, and returns that element
        render: function () {
            var rendered = Mustache.render(filtersTemplate, this.model.toJSON());
            this.$el.html(rendered);
            return this;
        },
        // Removes the filter
        remove: function () {
            var filterID = this.model.attributes.filterID,
                filterFound = FiltersCollection.findWhere({filterID: filterID});
            FiltersCollection.remove(filterFound);
            this.$el.remove();
        }
    });
    return DateFilter;
});