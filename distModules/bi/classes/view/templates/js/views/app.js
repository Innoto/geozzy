'use strict';

define([
    'jquery',
    'underscore',
    'q',
    'backbone',
    'models/statistics/metrics',
    'models/statistics/metricDynamic',
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
], function($,_,q,Backbone,MetricsModel,MetricsDynamicModel,OrgsModel,DetailByModel,StatsModel,FilterTypesModel,
            FilterTitleModel,CategoryTerms,Topics,FiltersCollection,MetricsView,OrgsView,DetailByView,FiltersSelectorView,
            BoxFilterView,SliderFilter,AreaFilter,DateFilterView,BarChartView,DateChartView,HeatMapView,
            HeatMapZonesView,FilterUtils,idUtils,Config){

    var AppView = Backbone.View.extend({
        el: '#statisticsModule',
        events: {
            'click #newAnalysis': 'resetAnalysis',
            'click #applyFilters': 'requestStatisticsHandler'
        },
        initialize: function () {
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
        checkCollection: function(){
            if (FiltersCollection.length > 0){
                this.$applyButton.show();
            } else {
                this.$applyButton.hide();
                this.orgChanged();
            }
        },
        metricChanged: function(){
            this.checkMetricTypeChanged();
            this.resetChart();
            this.resetDetailBy();
            var metricID = MetricsModel.get('selectedMetric');
            if (!_.isEmpty(metricID)){
                OrgsModel.clear();
                this.requestOrgsFilters();
                this.setCurrentMetric(metricID);
            }else{
                this.resetOrgs();
                this.resetFilters();
            }
        },
        checkMetricTypeChanged: function(){
            var previousMetricType = MetricsModel.getMetricType(this.currentMetric),
                currentMetricType  = MetricsModel.getMetricType(MetricsModel.get('selectedMetric'));

            if (!_.isEqual(previousMetricType,currentMetricType)){
                this.resetFilters();
            }
        },
        setCurrentMetric: function(metricID){
            this.currentMetric = metricID;
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
                this.requestStatisticsHandler();
            }else{
                this.resetDetailBy();
            }
        },
        detailByChanged: function(){
            this.requestStatisticsHandler();
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
            var filterID = filterModel.get('filterID'),
                metricType = MetricsModel.getMetricType(MetricsModel.get('selectedMetric'));

            var filterTitle = new FilterTitleModel({
                filterID: filterID,
                metricType: metricType
            });

            var that = this;
            filterTitle.fetch({
                success: function(){
                    filterModel.set('title',filterTitle.get('title'));
                    var filterView = new FilterView({
                        model: filterModel
                    });
                    that.$filters.append(filterView.render().$el);
                    if (!_.isUndefined(filterView.renderMap)){
                        filterView.renderMap();
                    }
                }
            });
        },
        resetRequest: function(){
            if (!_.isUndefined(this.requestStats)){
                this.requestStats.abort();
            }
            this.$loading.hide();
        },
        resetAnalysis: function(){
            this.resetRequest();
            this.resetOrgs();
            this.resetFilters();
            this.resetDetailBy();
            this.resetChart();
            MetricsModel.clear();
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
            this.$applyButton.hide();
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
            var filterID = filter.attributes.filterID,
                filterUrl = FilterUtils.getFilterUrl(filterID),
                FilterView = FilterUtils.getFilterView(filterID);

            this.checkCollection();
            if (!_.isUndefined(FilterView)) {
                if (!_.isUndefined(filterUrl)){
                    filter.set({
                        url: filterUrl
                    });
                    this.addRequestFilter(FilterView,filter);
                }else{
                    this.addFilter(FilterView,filter);
                }
            }
        },
        addRequestFilter: function(FilterView,filter){
            var that = this;
            if(_.isUndefined(filter.getTerms)){
                filter.fetch({
                    success: function(){
                        that.renderFilter(FilterView,filter);
                    }
                });
            }else{
                filter.getTerms()
                    .then(function(result){
                        var content = [];
                        _.each(result.elements, function(element){
                            content.push({
                                id: element.id,
                                name: element.categoryTermName
                            })
                        });
                        filter.set('content',content);
                        that.renderFilter(FilterView,filter);
                    });
            }
        },
        addFilter: function(FilterView,filter){
            this.renderFilter(FilterView,filter);
        },
        renderChartHandler: function(metricID,groupByID,chartType,chartData,countryID) {
            this.resetChart();
            var groups = [],
                data = [];
            var that = this;
            groupByID = parseInt(groupByID);
            idUtils.getIDModel(groupByID)
                .then(function(names){
                    names = (!_.isUndefined(names) && !_.isUndefined(names.elements))? names.elements : names;
                    _.each(chartData, function (elementData,index) {
                        if (!_.isUndefined(names)){
                            var id = (!_.isUndefined(elementData.group.groupName))? elementData.group.groupName : elementData.group;
                            var name = undefined;
                            for(var i = 0; i<names.length; i++){
                                var element = names[i];
                                if (element.id == id){
                                    name = (!_.isUndefined(element.name))? element.name : element;
                                    break;
                                }
                            }
                            name = (_.isUndefined(name))? id : name;
                            if (!_.isUndefined(chartData[index].group.groupName)){
                                chartData[index].group.groupName = name;
                            }else{
                                chartData[index].group = name;
                            }
                            groups.push(name);
                            data.push(elementData.data);
                        }else{
                            groups.push(elementData.group);
                            data.push(elementData.data);
                        }
                    });
                    that.renderChart(metricID,chartType,chartData,countryID,groups,data);
                });
        },
        renderChart: function(metricID,chartType,chartData,countryID,groups,data){
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
                    this.chart = new HeatMapZonesView({ chartData: chartData });
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
                    var categoryTerms = new CategoryTerms();
                    categoryTerms.getTerms()
                        .then(function (result){
                            MetricsModel.set('filterMetrics',result.elements);
                            that.renderMetrics();
                        });
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
        requestStatisticsHandler: function(){
            var metricID = MetricsModel.get('selectedMetric'),
                filterID = MetricsModel.get('selectedFilterID'),
                organizationID = OrgsModel.get('selectedOrg'),
                filters=[],
                detailByID = DetailByModel.get('selectedDetailBy');

            if (!_.isUndefined(metricID) && !_.isEmpty(metricID) &&
                !_.isUndefined(organizationID) && !_.isEmpty(organizationID)){

                this.resetRequest();
                this.resetChart();

                if (!_.isUndefined(filterID)){
                    FilterUtils.processMetricFilters(filters,filterID);
                }

                var that=this;
                FiltersCollection.each(function(filter){
                    switch (filter.attributes.filterID){
                        case Config.FILTER_DATE_RANGE:
                            FilterUtils.processDateFilters(filters,filter,that.$('#fromDate').val(),that.$('#toDate').val());
                            break;
                        default:
                            FilterUtils.processFilter(filters,filter);
                            break;
                    }
                });
                this.requestStatistics(metricID,organizationID,filters,detailByID);
            }
        },
        requestStatistics: function(metricID,organizationID,filters,detailByID){
            var payload = {
                metricID: metricID,
                groupByID: organizationID,
                filters: filters
            };
            if (!_.isUndefined(detailByID) && !_.isEmpty(detailByID)){
                payload["countryID"] = detailByID;
            }

            StatsModel.clear().set(payload);

            this.$loading.show();
            var that = this;
            this.requestStats =
                StatsModel.requestStats({
                    success: function(response){
                        that.$loading.hide();
                        that.renderChartHandler(payload.metricID,payload.groupByID,
                                                    response.chartType,response.data,response.countryID);
                    },
                    error: function(){
                        that.$loading.hide();
                        that.resetChart();
                    }
                });
        }
    });
    return AppView;
});