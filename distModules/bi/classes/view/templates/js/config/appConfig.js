'use strict';

define([], function () {
    var filterResource= 3,
        filterResourceGroup= 4,
        filterResourceTypes= 5,
        filterResourceTopics= 6,
        filterResourceTerms= 7,
        filterEventTypes= 8,
        filterRegionCountry= 9,
        filterDeviceType= 10,
        filterLanguage= 11,
        filterSection= 15,
        filterExplorer= 16,
        filterTaxonomyTerms= 17;
    return {
        'URL_STATS': 'http://test.geozzy.itg.es:10164/statistics',
        'URL_STATS_CONFIG': 'http://test.geozzy.itg.es:10164/statistics/config',
        'URL_INNOTO': 'geozzyapp',
        'URL_OPENSTREETMAP': 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',

        GROUP_BY_CLIENT_LOCATION: 10,

        FILTER_RESOURCE: filterResource,
        FILTER_RESOURCE_GROUP: filterResourceGroup,
        FILTER_RESOURCE_TYPES: filterResourceTypes,
        FILTER_RESOURCE_TOPICS: filterResourceTopics,
        FILTER_RESOURCE_TERMS: filterResourceTerms,
        FILTER_EVENT_TYPES: filterEventTypes,
        FILTER_REGION_COUNTRY: filterRegionCountry,
        FILTER_DEVICE_TYPE: filterDeviceType,
        FILTER_LANGUAGE: filterLanguage,
        FILTER_SECTION: filterSection,
        FILTER_EXPLORER: filterExplorer,
        FILTER_TAXONOMY_TERMS: filterTaxonomyTerms,

        FILTERS: [
            {
                filterID: filterResource,
                url: ''
            },
            {
                filterID: filterResourceGroup,
                url: ''
            },
            {
                filterID: filterResourceTypes,
                url: ''
            },
            {
                filterID: filterResourceTopics,
                url: ''
            },
            {
                filterID: filterResourceTerms,
                url: ''
            },
            {
                filterID: filterEventTypes,
                url: 'http://geozzyapp:80/api/core/uieventlist',
            },
            {
                filterID: filterRegionCountry,
                url: ''
            },
            {
                filterID: filterDeviceType,
                url: 'http://geozzyapp/api/core/bi'
            },
            {
                filterID: filterLanguage,
                url: 'http://geozzyapp/api/core/bi'
            },
            {
                filterID: filterSection,
                url: 'http://geozzyapp/api/core/bi'
            },
            {
                filterID: filterExplorer,
                url: ''
            },
            {
                filterID: filterTaxonomyTerms,
                url: ''
            }
        ],

        'BAR_CHART': 1,
        'HEAT_MAP': 2,
        'DATE_CHART': 3,
        'COUNTRY_BAR_CHART': 4,
        'MAP_EXPLORED_ZONES': 5
    };
});
