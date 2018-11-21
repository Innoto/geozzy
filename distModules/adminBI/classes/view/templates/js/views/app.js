//### Application's Main File: Manages the whole View
'use strict';

define([
    'underscore',
    'q',
    'backbone',
    'models/statistics/metrics',
    'models/statistics/organizations',
    'models/statistics/detailBy',
    'models/statistics/statistics',
    'models/filters/filterTypes',
    'models/filters/filterTitle',
    'models/categories/categoryTerms',
    'models/topics/topics',
    'collections/filters/filters',
    'views/statistics/metrics',
    'views/statistics/organizations',
    'views/statistics/detailBy',
    'views/filters/filtersSelector',
    'views/filters/boxFilter',
    'views/filters/dateFilter',
    'views/filters/sliderFilter',
    'views/filters/areaFilter',
    'views/charts/barChart',
    'views/charts/dateChart',
    'views/maps/heatMap',
    'views/maps/heatMapZones',
    'utils/filterUtils',
    'utils/idUtils',
    'config/appConfig'
], function (_, q, Backbone, MetricsModel, OrgsModel, DetailByModel,
             StatsModel, FilterTypesModel, FilterTitleModel, CategoryTerms, Topics, FiltersCollection,
             MetricsView, OrgsView, DetailByView, FiltersSelectorView,
             BoxFilterView, SliderFilter, AreaFilter, DateFilterView, BarChartView, DateChartView, HeatMapView,
             HeatMapZonesView, FilterUtils, idUtils, Config) {
    //* Main method where everything is controlled and rendered
    //* Every view & subview manages its own events.

    var AppView = Backbone.View.extend({
        el: '#statisticsModule',
        events: {
            'click #newAnalysis': 'resetAnalysis',
            'click #applyFilters': 'requestStatisticsHandler'
        },
        //* Creating all selectors of the view
        //* Listening to some events of change in the view
        //* Requesting Metrics and resetting the Organizations and Filters for the first time
        initialize: function () {
            cogumelo.log("BI: initialize");
            this.setElement($(this.el));      // jQuery template                          
            this.delegateEvents();

            this.$metricSelect = this.$('#metricsSelect');
            this.$orgsSelect = this.$('#orgsSelect');
            this.$detailBySelect = this.$('#detailBySelect');
            this.$filterSelector = this.$('#filtersSelector');
            this.$filters = this.$('#filters');
            this.$loading = this.$('#loading');
            this.$applyButton = this.$('#applyButton').hide();

            this.listenTo(FiltersCollection, 'add', this.addFilterHandler);
            this.listenTo(FiltersCollection, 'remove', this.checkCollection);
            this.listenTo(MetricsModel, 'change:selectedMetric', this.metricChanged);
            this.listenTo(OrgsModel, 'change:selectedOrg', this.orgChanged);
            this.listenTo(DetailByModel, 'change:selectedDetailBy', this.detailByChanged);

            this.requestMetrics();
            this.resetOrgs();
            this.resetFilters();
        },
        // If there are no filters selected, the application button is going to be hide.
        //* As there are no filters, the organization changes if the last filter was erased
        checkCollection: function () {
            if (FiltersCollection.length > 0) {
                this.$applyButton.show();
            } else {
                this.$applyButton.hide();
                this.orgChanged();
            }
        },
        // Activated when metric has changed
        //* If the selected metric is empty, the Orgs and Filters will be reset. If not, clear the Orgs, request the Filters and set the new Metric
        metricChanged: function () {
            this.checkMetricTypeChanged();
            this.resetChart();
            this.resetDetailBy();
            var metricID = MetricsModel.get('selectedMetric');

            if (!_.isEmpty(metricID)) {
                OrgsModel.clear();
                this.requestOrgsFilters();
                this.setCurrentMetric(metricID);
            } else {
                this.resetOrgs();
                this.resetFilters();
            }
        },
        // Checking if the new selected Metric is the same as the previous or not. If it is different the Filters are going to be resetted
        checkMetricTypeChanged: function () {
            var previousMetricType = MetricsModel.getMetricType(this.currentMetric),
                currentMetricType = MetricsModel.getMetricType(MetricsModel.get('selectedMetric'));

            if (!_.isEqual(previousMetricType, currentMetricType)) {
                this.resetFilters();
            }
        },
        // Sets the metric ID currently selected
        setCurrentMetric: function (metricID) {
            this.currentMetric = metricID;
        },
        // Used when Organization changes
        //* Gets the new Organization
        //* If an Org is selected, it makes the request, with another one for the Client Location if the organization is that one (and reset it if the case that no)
        //* If there is none selected, the Detail By will be reset
        orgChanged: function () {
            this.resetChart();
            var organizationID = OrgsModel.get('selectedOrg');
            if (!_.isEmpty(organizationID)) {
                if (organizationID == Config.GROUP_BY_CLIENT_LOCATION) {
                    this.requestDetailBy();
                } else {
                    this.resetDetailBy();
                }
                this.requestStatisticsHandler();
            } else {
                this.resetDetailBy();
            }
        },
        // If Detail By Client Location changes, the new statistics will be requested
        detailByChanged: function () {
            this.requestStatisticsHandler();
        },
        // Shows the dropdown with the metrics when clicked
        //* Create view from the model, and then the HTML
        renderMetrics: function () {
            var metricsView = new MetricsView({model: MetricsModel});
            this.$metricSelect.html(metricsView.render().$el);
        },
        // Shows the dropdown with the organization when clicked
        //* Create view from the model, and then the HTML
        renderOrgs: function () {
            var orgsView = new OrgsView({model: OrgsModel});
            this.$orgsSelect.html(orgsView.render().$el);
        },
        // Shows the dropdown with the filters when clicked
        //* Create view from the model and the correct collection of filters, and then the HTML
        renderFiltersSelector: function () {
            var filtersSelectorView = new FiltersSelectorView({
                model: FilterTypesModel,
                collection: FiltersCollection
            });
            this.$filterSelector.html(filtersSelectorView.render().$el);
        },
        // Shows the dropdown with the Detail By Client Location when clicked
        //* Create view from the model, and then the HTML
        renderDetailBy: function () {
            var detailByView = new DetailByView({model: DetailByModel});
            this.$detailBySelect.html(detailByView.render().$el);
        },
        // Used for showing the selected filter
        //* Getting the Filter ID and the correspondent metric, then the Title for the Filter will be created
        renderFilter: function (FilterView, filterModel) {
            var filterID = filterModel.get('filterID'),
                metricType = MetricsModel.getMetricType(MetricsModel.get('selectedMetric'));

            var filterTitle = new FilterTitleModel({
                filterID: filterID,
                metricType: metricType
            });
            //* If the fetching success, the Title will be setted to the Filter
            //* Then creates the Filter View
            //* Finally it will be rendered
            //* In the case that the filter is a map, it will be rendered
            var that = this;
            filterTitle.fetch({
                success: function () {
                    filterModel.set('title', filterTitle.get('title'));
                    var filterView = new FilterView({
                        model: filterModel
                    });
                    that.$filters.append(filterView.render().$el);

                    if (!_.isUndefined(filterView.renderMap)) {
                        filterView.renderMap();
                    }
                }
            });
        },
        // Cancels the Stats requests and hide the loading logo
        resetRequest: function () {
            if (!_.isUndefined(this.requestStats)) {
                this.requestStats.abort();
            }
            this.$loading.hide();
        },
        // Resets the whole Analysis, resetting all the components
        resetAnalysis: function () {
            this.resetRequest();
            this.resetOrgs();
            this.resetFilters();
            this.resetDetailBy();
            this.resetChart();
            MetricsModel.clear();
            this.requestMetrics();
        },
        // Clears the metrics and renders it once again
        resetMetrics: function () {
            MetricsModel.clear();
            this.renderMetrics();
        },
        // Resets the organizations
        resetOrgs: function () {
            OrgsModel.clear();
            this.renderOrgs();
        },
        // Resets the whole Filters
        resetFilters: function () {
            FilterTypesModel.clear();
            this.renderFiltersSelector();
            FiltersCollection.reset();
            this.$filters.html('');
            this.$applyButton.hide();
        },
        // Resets the Detail By Client Location selector
        resetDetailBy: function () {
            DetailByModel.clear();
            this.$detailBySelect.html('');
        },
        // Clear the stats and removes the chart if it is showed
        resetChart: function () {
            StatsModel.clear();
            if (!_.isUndefined(this.chart) && !_.isUndefined(this.chart.remove)) {
                this.chart.remove();
            }
        },
        // Manages adding filters. If it uses an URL, it will call to Requesting Filter, if not it will add it directly
        addFilterHandler: function (filter) {
            var filterID = filter.attributes.filterID,
                filterUrl = FilterUtils.getFilterUrl(filterID),
                FilterView = FilterUtils.getFilterView(filterID);

            this.checkCollection();
            if (!_.isUndefined(FilterView)) {
                if (!_.isUndefined(filterUrl)) {
                    filter.set({
                        url: filterUrl
                    });
                    this.addRequestFilter(FilterView, filter);
                } else {
                    this.renderFilter(FilterView, filter);
                }
            }
        },
        // Requests Filters in the case that uses an URL. If there are no Terms to load (undefined), it will make the fetch and if it success it will render.
        // In the another case (there are Terms to get), it will get it, and for each element, it is going to push it on the Content List with the ID and Name fields.
        addRequestFilter: function (FilterView, filter) {
            var that = this;
            if (_.isUndefined(filter.getTerms)) {
                filter.fetch({
                    success: function () {
                        that.renderFilter(FilterView, filter);
                    }
                });
            } else {
                filter.getTerms()
                    .then(function (result) {
                        var content = [];
                        _.each(result.elements, function (element) {
                            content.push({
                                id: element.id,
                                name: element.categoryTermName
                            })
                        });
                        filter.set('content', content);
                        that.renderFilter(FilterView, filter);
                    });
            }
        },
        // Manages the rendering of a chart. Calls for rendering the chart after controlling and choosing which nameis going to show on the chart for the attribute: If the ID or the name 'per se', depending on the type.
        renderChartHandler: function (metricID, groupByID, chartType, chartData, countryID) {
            this.resetChart();
            var groups = [],
                data = [];
            var that = this;
            groupByID = parseInt(groupByID);
            idUtils.getIDModel(groupByID)
                .then(function (names) {
                    names = (!_.isUndefined(names) && !_.isUndefined(names.elements)) ? names.elements : names
                    _.each(chartData, function (elementData, index) {
                        if (!_.isUndefined(names)) {
                            var id = (!_.isUndefined(elementData.group.groupName)) ? elementData.group.groupName : elementData.group;
                            var name = undefined;
                            for (var i = 0; i < names.length; i++) {
                                var element = names[i];
                                if (element.id == id) {
                                    name = (!_.isUndefined(element.name)) ? element.name : element;
                                    break;
                                }
                            }
                            name = (_.isUndefined(name)) ? id : name;
                            if (!_.isUndefined(chartData[index].group.groupName)) {
                                chartData[index].group.groupName = name;
                            } else {
                                chartData[index].group = name;
                            }
                            groups.push(name);
                            data.push(elementData.data);
                        } else {
                            groups.push(elementData.group);
                            data.push(elementData.data);
                        }
                    });
                    that.renderChart(metricID, chartType, chartData, countryID, groups, data);
                });
        },
        // Creates a new Chart View depending of the Chart Type. If it's undefined it shows an "Chart Not Found" message
        renderChart: function (metricID, chartType, chartData, countryID, groups, data) {
            switch (chartType) {
                case Config.BAR_CHART:
                    this.chart = new BarChartView({groups: groups, data: data});
                    break;
                case Config.HEAT_MAP:
                    this.chart = new HeatMapView({chartData: chartData});
                    break;
                case Config.DATE_CHART:
                    this.chart = new DateChartView({groups: groups, data: data});
                    break;
                case Config.COUNTRY_BAR_CHART:
                    this.chart = new BarChartView({groups: groups, data: data});
                    if (_.isUndefined(countryID)) {
                        DetailByModel.set({'countryIDs': groups});
                        this.requestDetailBy();
                    }
                    break;
                case Config.MAP_EXPLORED_ZONES:
                    this.chart = new HeatMapZonesView({chartData: chartData});
                    break;
            }
            if (!_.isUndefined(this.chart)) {
                this.chart.render();
            } else {
                cogumelo.log("Chart " + chartType + " not found");
            }
        },
        // Requests the metrics to show. If the fetch success, it will get the Terms and then renders them. If not, only resets Metrics and Charts.
        requestMetrics: function () {
            var now = new Date(),
                metricsDate = window.localStorage.getItem('metricsDate');

            var diffMilis;
            if (!_.isNull(metricsDate)){
                metricsDate = new Date(metricsDate);
                diffMilis = now.getTime() - metricsDate.getTime();
            }
            if (!_.isUndefined(diffMilis) && diffMilis < Config.METRICS_CACHE_EXPIRATION){
                var metrics = JSON.parse(window.localStorage.getItem('metrics')),
                    filterMetrics = JSON.parse(window.localStorage.getItem('filterMetrics'));
                MetricsModel.set('metrics', metrics);
                MetricsModel.set('filterMetrics', filterMetrics);
                this.renderMetrics();
            }else{
                var that = this;
                MetricsModel.fetch({
                    success: function () {
                        var categoryTerms = new CategoryTerms();
                        categoryTerms.getTerms()
                            .then(function (result) {
                                MetricsModel.set('filterMetrics', result.elements);
                                window.localStorage.setItem('metrics',JSON.stringify(MetricsModel.get('metrics')));
                                window.localStorage.setItem('filterMetrics',JSON.stringify(MetricsModel.get('filterMetrics')));
                                window.localStorage.setItem('metricsDate', new Date());
                                that.renderMetrics();
                            });
                    },
                    error: function () {
                        that.resetMetrics();
                        that.resetChart();
                    }
                });
            }
        },
        // Requests the Organizations and the Filters once is clicked on the Metric dropdown. If there is not a selected Metric it resets Orgs, Filters and Charts.
        // If there is a valid Metric, it sets the Orgs and Filters, and if the fetch success in the both cases, it will render them. If there is an Error, it will reset the Chart, and Orgs and/or Filters.
        requestOrgsFilters: function () {
            var metricID = MetricsModel.get('selectedMetric');
            if (!_.isEmpty(metricID)) {
                var that = this;
                OrgsModel.set('metricID', metricID);
                OrgsModel.fetch({
                    success: function () {
                        that.renderOrgs();
                    },
                    error: function () {
                        that.resetOrgs();
                        that.resetChart();
                    }
                });
                FilterTypesModel.set('metricID', metricID);
                FilterTypesModel.fetch({
                    success: function () {
                        that.renderFiltersSelector();
                    },
                    error: function () {
                        that.resetFilters();
                        that.resetChart();
                    }
                });
            } else {
                this.resetOrgs();
                this.resetFilters();
                this.resetChart();
            }
        },
        // Requests the Detail By Countries. If success, it will set the countries and render them, if not, it will reset the Detail By.
        requestDetailBy: function () {
            var that = this;
            DetailByModel.requestCountries({
                success: function (countries) {
                    DetailByModel.set('countries', countries);
                    that.renderDetailBy();
                },
                error: function () {
                    that.resetDetailBy();
                }
            });
        },
        // Manages the Statistics Request. Get all of the selections, and if they are not undefined, Chart and Request are resetted.
        // If the there is a Filter ID then calls for pushing on the filters list the explorer's filters. Finally, for each filter on List, process it (choosing if it is a Date or another different filter).
        // Then applies the Statistics Request.
        requestStatisticsHandler: function () {
            var metricID = MetricsModel.get('selectedMetric'),
                filterID = MetricsModel.get('selectedFilterID'),
                organizationID = OrgsModel.get('selectedOrg'),
                filters = [],
                detailByID = DetailByModel.get('selectedDetailBy');

            if (!_.isUndefined(metricID) && !_.isEmpty(metricID) && !_.isUndefined(organizationID) && !_.isEmpty(organizationID)) {

                this.resetRequest();
                this.resetChart();

                if (!_.isUndefined(filterID)) {
                    FilterUtils.processMetricFilters(filters, filterID);
                }

                var that = this;
                FiltersCollection.each(function (filter) {
                    switch (filter.attributes.filterID) {
                        case Config.FILTER_DATE_RANGE:
                            FilterUtils.processDateFilters(filters, filter, that.$('#fromDate').val(), that.$('#toDate').val());
                            break;
                        default:
                            FilterUtils.processFilter(filters, filter);
                            break;
                    }
                });
                this.requestStatistics(metricID, organizationID, filters, detailByID);
            }
        },
        // Controls the Detail By Client Location special case, and loads the Detail By ID. Clear the current Stats, and then make the new Request.
        // If success then Render the Chart Handler, if not only reset the Chart.
        requestStatistics: function (metricID, organizationID, filters, detailByID) {
            var payload = {
                metricID: metricID,
                groupByID: organizationID,
                filters: filters
            };
            if (!_.isUndefined(detailByID) && !_.isEmpty(detailByID)) {
                payload["countryID"] = detailByID;
            }
            StatsModel.clear().set(payload);

            this.$loading.show();
            var that = this;
            this.requestStats =
                StatsModel.requestStats({
                    success: function (response) {
                        that.$loading.hide();
                        that.renderChartHandler(payload.metricID, payload.groupByID,
                            response.chartType, response.data, response.countryID);
                    },
                    error: function () {
                        that.$loading.hide();
                        that.resetChart();
                    }
                });
        }
    });
    return AppView;
});