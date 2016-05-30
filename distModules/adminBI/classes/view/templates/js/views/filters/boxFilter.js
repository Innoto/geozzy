//### Box Filter View
define([
    'jquery',
    'backbone',
    'mustache',
    'text!templates/filters/boxFilter.html',
    'collections/filters/filters',
    'select2'
], function ($, Backbone, Mustache, filtersTemplate, FiltersCollection) {
    // Creating BoxFilter template, used for filtering by different options selected
    var BoxFilter = Backbone.View.extend({
        tagName: 'div',
        events: {
            'click #close': 'remove',
            'change .selectMultiple': 'optionSelected'
        },
        // Rendering the BoxFilter template, and returns that element
        render: function () {
            var rendered = Mustache.render(filtersTemplate, this.model.toJSON());
            this.$el.html(rendered);
            this.$el.find('select').select2({width: "95%"});
            return this;
        },
        // Removes the filter
        remove: function () {
            var filterID = this.model.attributes.filterID,
                filterFound = FiltersCollection.findWhere({filterID: filterID});
            FiltersCollection.remove(filterFound);
            this.$el.remove();
        },
        // Sets the new set of values when there is a change on it
        optionSelected: function (e) {
            var values = $(e.target).val();
            this.model.set("values", values);
        }
    });
    return BoxFilter;
});


