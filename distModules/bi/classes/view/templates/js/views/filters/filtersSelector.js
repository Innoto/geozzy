//### Filter Selector View
define([
    'jquery',
    'backbone',
    'mustache',
    'models/filters/filter',
    'text!templates/filters/filtersSelector.html',
    '../../utils/filterUtils'
], function ($, Backbone, Mustache, FilterModel, filtersTemplate, FilterUtils) {
    // Creating FilterView template, used for selecting the different filters to use
    var FiltersView = Backbone.View.extend({
        // Picking the new filter clicked event on the Selector
        events: {
            'click .filterSelectorItem': 'filterClicked'
        },
        // Rendering the FilterSelector template, and returns that element
        render: function () {
            var rendered = Mustache.render(filtersTemplate, this.model.toJSON());
            this.$el.html(rendered);
            return this;
        },
        // Picks the ID from the filter selected, search on the collection, and gets the Filter Model for adding it to the collection
        filterClicked: function (e) {
            var filterID = parseInt($(e.target).attr("filterID")),
                filterFound = this.collection.findWhere({filterID: filterID});
            // If it is a valid filterID, get the model and makes the adding
            if (!_.isNaN(filterID) && !_.isUndefined(filterID) && _.isUndefined(filterFound)) {
                var filter = FilterUtils.getFilterModel(filterID);
                this.collection.add(filter);
            }
        }
    });
    return FiltersView;
});
