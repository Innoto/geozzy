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
        tagName: 'div',
        events: {
            'click #close': 'remove',
            'change #sl': 'sliderChange'
        },
        // Rendering the Slider template, and returns that element
        render: function () {
            var rendered = Mustache.render(filtersTemplate, this.model.toJSON());
            this.$el.html(rendered);
            this.slider = this.$el.find('#sl').slider({
                formatter: function (value) {
                    return value + ' seg.';
                }
            });
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
            var values = [this.slider.slider('getValue')];
            this.model.set("values", values);
        }
    });
    return SliderFilter;
});