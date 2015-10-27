//### App Configuration
'use strict';

define([], function () {
    // Filters ID assigned to the names
    var filterResource = 3,
        filterResourceTypes = 5,
        filterResourceTopics = 6,
        filterResourceTerms = 7,
        filterEventTypes = 8,
        filterRegionCountry = 9,
        filterDeviceType = 10,
        filterLanguage = 11,
        filterDateRange = 12,
        filterResourceGeographicBound = 13,
        filterExplorerGeographicBound = 14,
        filterSection = 15,
        filterExplorer = 16,
        filterTaxonomyTerms = 17,
        filterSeconds = 18;
    // Variables for the both domains to access
    var urlBIserver = "http://test.geozzy.itg.es:10164",
        urlGeozzy = "http://galiciaagochada.nnt/api";

    return {
        // URLs assigned to their variables
        URL_BI_SERVER: urlBIserver,
        URL_STATS: urlBIserver + '/statistics',
        URL_STATS_CONFIG: urlBIserver + '/statistics/config',
        URL_GEOZZY: urlGeozzy,
        URL_CATEGORY: urlGeozzy + '/core/categorylist',
        URL_CATEGORY_TERMS: urlGeozzy + '/core/categoryterms/id/',
        URL_TOPICS: urlGeozzy + '/core/topiclist',
        URL_EXPLORERS: urlGeozzy + '/explorerList',
        URL_OPENSTREETMAP: 'http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
        // Specifies the date format used
        DATE_FORMAT: 'D/M/YYYY HH:mm:ss',
        // Filter Explorer ID assigned to the variable
        FILTER_EXPLORERS_FILTERS_ID: 19,
        // Group By ID assigned to the variables
        GROUP_BY_RESOURCE_TOPIC: 3,
        GROUP_BY_RESOURCE_TERM: 4,
        GROUP_BY_CLIENT_LOCATION: 10,
        GROUP_BY_EXPLORER: 12,
        GROUP_BY_FILTER: 13,
        GROUP_BY_EXPLORER_BOUNDS: 14,
        GROUP_BY_FILTER_BOUNDS: 15,
        // Filter ID names assigned to the variables
        FILTER_RESOURCE: filterResource,
        FILTER_RESOURCE_TYPES: filterResourceTypes,
        FILTER_RESOURCE_TOPICS: filterResourceTopics,
        FILTER_RESOURCE_TERMS: filterResourceTerms,
        FILTER_EVENT_TYPES: filterEventTypes,
        FILTER_REGION_COUNTRY: filterRegionCountry,
        FILTER_DEVICE_TYPE: filterDeviceType,
        FILTER_LANGUAGE: filterLanguage,
        FILTER_DATE_RANGE: filterDateRange,
        FILTER_RESOURCE_GEOGRAPHIC_BOUND: filterResourceGeographicBound,
        FILTER_EXPLORER_GEOGRAPHIC_BOUND: filterExplorerGeographicBound,
        FILTER_SECTION: filterSection,
        FILTER_EXPLORER: filterExplorer,
        FILTER_TAXONOMY_TERMS: filterTaxonomyTerms,
        FILTER_SECONDS: filterSeconds,
        // Parsing the Filter URL with the different ID and urls
        FILTER_URL: [
            {
                filterID: filterResource,
                url: urlGeozzy + '/core/resourcelist/fields/false/filters/false/rtype/false'
            },
            {
                filterID: filterResourceTypes,
                url: urlGeozzy + '/core/resourcetypes'
            },
            {
                filterID: filterResourceTopics,
                url: urlGeozzy + '/core/topiclist'
            },
            {
                filterID: filterResourceTerms,
                url: urlGeozzy + '/core/categorylist'
            },
            {
                filterID: filterEventTypes,
                url: urlGeozzy + '/core/uieventlist'
            },
            {
                filterID: filterRegionCountry,
                url: urlGeozzy
            },
            {
                filterID: filterDeviceType,
                url: urlGeozzy + '/core/bi'
            },
            {
                filterID: filterLanguage,
                url: urlGeozzy + '/core/bi'
            },
            {
                filterID: filterSection,
                url: urlGeozzy + '/core/bi'
            },
            {
                filterID: filterExplorer,
                url: urlGeozzy + '/explorerList'
            },
            {
                filterID: filterTaxonomyTerms,
                url: urlGeozzy + '/core/categorylist'
            }
        ],
        // Chart Type ID assigned to the variables
        'BAR_CHART': 1,
        'HEAT_MAP': 2,
        'DATE_CHART': 3,
        'COUNTRY_BAR_CHART': 4,
        'MAP_EXPLORED_ZONES': 5
    };
});
