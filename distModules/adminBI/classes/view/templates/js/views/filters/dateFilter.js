//### Date Filter View
define([
    'jquery',
    'backbone',
    'mustache',
    'text!templates/filters/dateFilter.html',
    'collections/filters/filters',
    'datetimepicker',
    'config/appConfig'
], function ($, Backbone, Mustache, filtersTemplate, FiltersCollection, DateTimePicker, Config) {
    // Creating DateFilter template, used for filtering by Date
    var DateFilter = Backbone.View.extend({
        tagName: 'div',
        events: {
            'click #close': 'remove'
        },
        // Rendering the DateFilter template, and returns that element
        render: function () {
            var rendered = Mustache.render(filtersTemplate, this.model.toJSON());
            this.$el.html(rendered);
            var configDate = {
                locale: Config.LOCALE,
                format: Config.DATE_FORMAT
            };
            this.$el.find('#fromDateDiv').datetimepicker(configDate);
            this.$el.find('#toDateDiv').datetimepicker(configDate);
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