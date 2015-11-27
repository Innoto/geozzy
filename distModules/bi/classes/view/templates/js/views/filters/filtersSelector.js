//### Filter Selector View
define([
    'backbone',
    'mustache',
    'models/filters/filter',
    'text!templates/filters/filtersSelector.html',
    '../../utils/filterUtils'
], function (Backbone, Mustache, FilterModel, filtersTemplate, FilterUtils) {
    // Creating FilterView template, used for selecting the different filters to use
    var FiltersView = Backbone.View.extend({
        events: {
            'click .filterSelectorItem': 'filterClicked'
        },
        // Render the FilterSelector template, and returns that element
        render: function () {
            var rendered = Mustache.render(filtersTemplate, this.model.toJSON());
            this.$el.html(rendered);
            return this;
        },
        // Picks the ID from the filter selected, search on the collection,
        // and gets the Filter Model for adding it to the collection
        filterClicked: function (e) {
            var filterID = parseInt($(e.target).attr("filterID")),
                filterFound = this.collection.findWhere({filterID: filterID});
            if (!_.isNaN(filterID) && !_.isUndefined(filterID) && _.isUndefined(filterFound)) {
                var filter = FilterUtils.getFilterModel(filterID);
                this.collection.add(filter);
            }
        }
    });
    return FiltersView;
});
