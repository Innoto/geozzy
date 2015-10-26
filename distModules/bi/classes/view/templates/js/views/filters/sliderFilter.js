//### Slider Filter View
define([
    'jquery',
    'backbone',
    'mustache',
    'text!templates/filters/sliderFilter.html',
    'collections/filters/filters',
    'bootstrap-slider'
], function ($, Backbone, Mustache, filtersTemplate, FiltersCollection, Slider) {
    // Creating SliderFilter template, used for filtering by Duration
    var SliderFilter = Backbone.View.extend({
        // Default value for tagName
        tagName: 'div',
        // Picking the Closing and Change events on the slider
        events: {
            'click #close': 'remove',
            'change #sl': 'sliderChange'
        },
        // Initializes the filter element
        initialize: function () {
            console.log($("input.slider"));
        },
        // Rendering the Slider template, and returns that element
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
        },
        // Sets the new value when there is a change on the slider
        sliderChange: function () {
            var value = parseInt(this.$("#sl").attr('data-value'));
            var values = [value];
            this.model.set("values", values);
        }
    });
    return SliderFilter;
});