//### Utils file for processing, parsing and configuring the filters
'use strict';

define([
    'underscore',
    'q',
    'moment',
    'views/filters/boxFilter',
    'views/filters/dateFilter',
    'views/filters/areaFilter',
    'views/filters/sliderFilter',
    'models/filters/filter',
    'models/categories/categoryTerms',
    'config/appConfig'
], function (_, q, moment, BoxFilterView, DateFilterView, MapFilterView, SliderFilterView, Filter, CategoryTerms, Config) {
    // Parses the date String into a valid format for processing with toDate and toISOString
    var formatDate = function (dateString) {
        if (!_.isEmpty(dateString)) {
            var date = moment(dateString, Config.DATE_FORMAT).toDate().toISOString();
            return date;
        } else {
            return null;
        }
    };

    return {
        // Function used to get the correct Filter View using the ID
        //* Creating different lists with each type of filter: Box, Date, Map and Slider
        //* Searches the Filter ID into the different lists for getting the correct view to use
        getFilterView: function (filterID) {
            var listBox = [Config.FILTER_LANGUAGE, Config.FILTER_SECTION, Config.FILTER_EVENT_TYPES,
                    Config.FILTER_DEVICE_TYPE, Config.FILTER_RESOURCE,
                    Config.FILTER_RESOURCE_TERMS, Config.FILTER_RESOURCE_TOPICS, Config.FILTER_RESOURCE_TYPES,
                    Config.FILTER_EXPLORER, Config.FILTER_TAXONOMY_TERMS],
                listDate = [Config.FILTER_DATE_RANGE],
                listMap = [Config.FILTER_RESOURCE_GEOGRAPHIC_BOUND, Config.FILTER_EXPLORER_GEOGRAPHIC_BOUND],
                listSlider = [Config.FILTER_SECONDS];

            if (_.contains(listBox, filterID)) {
                return BoxFilterView;
            }
            if (_.contains(listDate, filterID)) {
                return DateFilterView;
            }
            if (_.contains(listMap, filterID)) {
                return MapFilterView;
            }
            if (_.contains(listSlider, filterID)) {
                return SliderFilterView;
            }
        },
        // Returns the URL from the determined filter of the Collection
        getFilterUrl: function (filterID) {
            var filterConfig = _.findWhere(Config.FILTERS, {filterID: filterID});
            if (!_.isUndefined(filterConfig) && !_.isUndefined(filterConfig.url)) {
                return filterConfig.url;
            } else {
                return undefined;
            }
        },
        // Returns the Filter Model from the determined filter of the Collection
        getFilterModel: function (filterID) {
            var filterCategoryTermIDs = [Config.FILTER_RESOURCE_TERMS, Config.FILTER_TAXONOMY_TERMS];
            if (_.contains(filterCategoryTermIDs, filterID)) {
                return new CategoryTerms({filterID: filterID});
            } else {
                return new Filter({filterID: filterID});
            }
        },
        // Process the date filters and push it into the Filters list
        //* If there is some start date, push it into the filter to add
        //* If there is some final date, push it into the filter to add
        processDateFilters: function (filters, dateFilter, fromDateString, toDateString) {
            var dateFilters = [];
            if (!_.isEmpty(fromDateString)) {
                dateFilters.push({
                    "operator": "$gte",
                    "value": formatDate(fromDateString)
                });
            }
            if (!_.isEmpty(toDateString)) {
                dateFilters.push({
                    "operator": "$lte",
                    "value": formatDate(toDateString)
                })
            }
            filters.push({
                filterID: dateFilter.attributes.filterID,
                values: dateFilters
            });
        },
        // Push the metric from explorers with his values into the Filters list
        processMetricFilters: function (filters, filterID) {
            filters.push({
                filterID: Config.FILTER_EXPLORERS_FILTERS_ID,
                values: [
                    {
                        '$eq': filterID
                    }
                ]
            });
        },
        // Push into the Filters list the new Filter with his ID and values
        processFilter: function (filters, filter) {
            filters.push({
                filterID: filter.attributes.filterID,
                values: filter.attributes.values
            });
        }
    };
});