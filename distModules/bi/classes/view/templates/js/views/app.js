'use strict';

define([
    'jquery',
    'underscore',
    'backbone',
    'models/statistics/metrics',
    'models/statistics/organizations',
    'models/statistics/detailBy',
    'models/statistics/statistics',
    'models/filters/filterTypes',
    'collections/filters/filters',
    'views/statistics/metrics',
    'views/statistics/organizations',
    'views/statistics/detailBy',
    'views/filters/filtersSelector',
    'views/filters/boxFilter',
    'views/filters/dateFilter',
    'views/charts/barChart',
    'views/charts/dateChart',
    'views/maps/heatMap',
    'views/maps/map',
    'views/utils/viewUtils',
    'config/appConfig'
], function($,_,Backbone,MetricsModel,OrgsModel,DetailByModel,StatsModel,FilterTypesModel,FiltersCollection,
            MetricsView,OrgsView,DetailByView,FiltersSelectorView,BoxFilterView,DateFilterView,BarChartView,
            DateChartView,HeatMapView,MapView,ViewUtils,Config){

    var AppView = Backbone.View.extend({
        el: '#statisticsModule',
        events: {
            'click #newAnalysis': 'resetAnalysis'
        },
        initialize: function () {
            this.$metricSelect = this.$('#metricsSelect');
            this.$orgsSelect = this.$('#orgsSelect');
            this.$detailBySelect = this.$('#detailBySelect');
            this.$filterSelector = this.$('#filtersSelector');
            this.$filters = this.$('#filters');
            this.$loading = this.$('#loading');

            this.listenTo(MetricsModel, 'change:selectedMetric', this.metricChanged);
            this.listenTo(OrgsModel, 'change:selectedOrg', this.orgChanged);
            this.listenTo(DetailByModel, 'change:selectedDetailBy', this.detailByChanged);

            this.listenTo(FiltersCollection, 'add', this.addFilterHandler);
            this.listenTo(FiltersCollection, 'remove', this.removeFilter);

            this.requestMetrics();
            this.resetOrgs();
            this.resetFilters();
        },
        metricChanged: function(){
            this.resetChart();
            this.resetDetailBy();
            var metricID = MetricsModel.get('selectedMetric');
            if (!_.isEmpty(metricID)){
                this.requestOrgsFilters();
            }else{
                this.resetOrgs();
                this.resetFilters();
            }
        },
        orgChanged: function(){
            this.resetChart();
            var organizationID = OrgsModel.get('selectedOrg');
            if (!_.isEmpty(organizationID)){
                if (organizationID == Config.GROUP_BY_CLIENT_LOCATION){
                    this.requestDetailBy();
                }else{
                    this.resetDetailBy();
                }
                this.requestStatistics();
            }else{
                this.resetDetailBy();
            }
        },
        detailByChanged: function(){
            this.requestStatistics();
        },
        renderMetrics: function() {
            var metricsView = new MetricsView({model: MetricsModel});
            this.$metricSelect.html(metricsView.render().$el);
        },
        renderOrgs: function() {
            var orgsView = new OrgsView({model: OrgsModel});
            this.$orgsSelect.html(orgsView.render().$el);
        },
        renderFiltersSelector: function() {
            var filtersSelectorView = new FiltersSelectorView({
                model: FilterTypesModel,
                collection: FiltersCollection
            });
            this.$filterSelector.html(filtersSelectorView.render().$el);
        },
        renderDetailBy: function() {
            var detailByView = new DetailByView({model: DetailByModel});
            this.$detailBySelect.html(detailByView.render().$el);
        },
        renderFilter: function(FilterView,filterModel){
            var filterView = new FilterView({
                model: filterModel
            });
            this.$filters.append(filterView.render().$el);
        },
        resetAnalysis: function(){
            this.resetOrgs();
            this.resetFilters();
            this.resetDetailBy();
            this.resetChart();
            this.requestMetrics();
        },
        resetMetrics: function() {
            MetricsModel.clear();
            this.renderMetrics();
        },
        resetOrgs: function() {
            OrgsModel.clear();
            this.renderOrgs();
        },
        resetFilters: function(){
            FilterTypesModel.clear();
            this.renderFiltersSelector();
            FiltersCollection.reset();
            this.$filters.html('');
        },
        resetDetailBy: function() {
            DetailByModel.clear();
            this.$detailBySelect.html('');
        },
        resetChart: function(){
            StatsModel.clear();
            if (!_.isUndefined(this.chart) && !_.isUndefined(this.chart.remove)){
                this.chart.remove();
            }
        },
        addFilterHandler: function(filter){
            console.log('Added Filter:');
            console.log(filter);
            var filterID = filter.attributes.filterID,
                filterFound = _.findWhere(Config.FILTERS, {filterID: filterID});
            if (!_.isUndefined(filterFound)) {
                var FilterView = ViewUtils.getFilterView(filterID),
                    url = !_.isUndefined(filterFound.url)? filterFound.url : '';
                if (!_.isEmpty(url)){
                    filter.set({
                        url: url
                    });
                    this.addRequestFilter(FilterView,filter);
                }else{
                    this.addFilter(FilterView,filter);
                }
            }
        },
        addRequestFilter: function(FilterView,filter){
            var that = this;
            filter.fetch({
                success: function(){
                    that.renderFilter(FilterView,filter);
                },
                error: function(){}
            });
        },
        addFilter: function(FilterView,filter){
            this.renderFilter(FilterView,filter);
        },
        removeFilter: function(filter){
            console.log('Removed Filter:');
            console.log(filter);
        },
        renderChart: function(metricID,chartType,chartData,countryID){
            this.resetChart();
            var groups = [],
                data = [];
            _.each(chartData, function(elementData){
                groups.push(elementData.group);
                data.push(elementData.data);
            });
            switch (chartType){
                case Config.BAR_CHART:
                    this.chart = new BarChartView({ groups: groups, data: data });
                    break;
                case Config.HEAT_MAP:
                    this.chart = new HeatMapView({ chartData: chartData });
                    break;
                case Config.DATE_CHART:
                    this.chart = new DateChartView({ groups: groups, data: data });
                    break;
                case Config.COUNTRY_BAR_CHART:
                    this.chart = new BarChartView({ groups: groups, data: data });
                    if (_.isUndefined(countryID)){
                        DetailByModel.set({'countryIDs':groups});
                        this.requestDetailBy();
                    }
                    break;
                case Config.MAP_EXPLORED_ZONES:
                    this.chart = new MapView({ chartData: chartData });
                    break;
            }
            if (!_.isUndefined(this.chart)){
                this.chart.render();
            }else{
                console.log("Chart "+chartType+" not found");
            }
        },
        requestMetrics: function() {
            var that = this;
            MetricsModel.fetch({
                success: function(){
                    that.renderMetrics();
                },
                error: function(){
                    that.resetMetrics();
                    that.resetChart();
                }
            });
        },
        requestOrgsFilters: function() {
            var metricID = MetricsModel.get('selectedMetric');
            if (!_.isEmpty(metricID)){
                var that = this;
                OrgsModel.set('metricID',metricID);
                OrgsModel.fetch({
                    success: function(){
                        that.renderOrgs();
                    },
                    error: function(){
                        that.resetOrgs();
                        that.resetChart();
                    }
                });
                FilterTypesModel.set('metricID',metricID);
                FilterTypesModel.fetch({
                    success: function(){
                        that.renderFiltersSelector();
                    },
                    error: function(){
                        that.resetFilters();
                        that.resetChart();
                    }
                });
            }else {
                this.resetOrgs();
                this.resetFilters();
                this.resetChart();
            }
        },
        requestDetailBy: function() {
            var that = this;
            DetailByModel.requestCountries({
                success: function(countries){
                    DetailByModel.set('countries',countries);
                    that.renderDetailBy();
                },
                error: function(){
                    that.resetDetailBy();
                }
            });
        },
        requestStatistics: function(){
            var metricID = MetricsModel.get('selectedMetric'),
                organizationID = OrgsModel.get('selectedOrg'),
                filters=[],
                detailByID = DetailByModel.get('selectedDetailBy');

            if (!_.isUndefined(metricID) && !_.isEmpty(metricID) &&
                !_.isUndefined(organizationID) && !_.isEmpty(organizationID)){
                FiltersCollection.each(function(filter){
                    filters.push({
                        filterID: filter.attributes.filterID,
                        values: filter.attributes.values
                    })
                });
                var payload = {
                    metricID: metricID,
                    groupByID: organizationID,
                    filters: filters
                };
                if (!_.isUndefined(detailByID) && !_.isEmpty(detailByID)){
                    payload["countryID"] = detailByID;
                }
                StatsModel.clear().set(payload);
                console.log('Stats Request');
                console.log(payload);
                this.$loading.show();
                var that = this;
                StatsModel.requestStats({
                    success: function(response){
                        that.$loading.hide();
                        console.log('Stats Response');
                        console.log(response);
                        that.renderChart(metricID,response.chartType,response.data,response.countryID);
                    },
                    error: function(){
                        that.$loading.hide();
                        that.resetChart();
                    }
                });
            }
        }
    });
    return AppView;
});