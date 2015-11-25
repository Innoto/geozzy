"use strict";define(["jquery","underscore","q","backbone","models/statistics/metrics","models/statistics/organizations","models/statistics/detailBy","models/statistics/statistics","models/filters/filterTypes","models/filters/filterTitle","models/categories/categoryTerms","models/topics/topics","collections/filters/filters","views/statistics/metrics","views/statistics/organizations","views/statistics/detailBy","views/filters/filtersSelector","views/filters/boxFilter","views/filters/dateFilter","views/filters/sliderFilter","views/filters/areaFilter","views/charts/barChart","views/charts/dateChart","views/maps/heatMap","views/maps/heatMapZones","utils/filterUtils","utils/idUtils","config/appConfig"],function(a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u,v,w,x,y,z,A,B){var C=d.View.extend({el:"#statisticsModule",events:{"click #newAnalysis":"resetAnalysis","click #applyFilters":"requestStatisticsHandler"},initialize:function(){this.$metricSelect=this.$("#metricsSelect"),this.$orgsSelect=this.$("#orgsSelect"),this.$detailBySelect=this.$("#detailBySelect"),this.$filterSelector=this.$("#filtersSelector"),this.$filters=this.$("#filters"),this.$loading=this.$("#loading"),this.$applyButton=this.$("#applyButton").hide(),this.listenTo(m,"add",this.addFilterHandler),this.listenTo(m,"remove",this.checkCollection),this.listenTo(e,"change:selectedMetric",this.metricChanged),this.listenTo(f,"change:selectedOrg",this.orgChanged),this.listenTo(g,"change:selectedDetailBy",this.detailByChanged),this.requestMetrics(),this.resetOrgs(),this.resetFilters()},checkCollection:function(){m.length>0?this.$applyButton.show():(this.$applyButton.hide(),this.orgChanged())},metricChanged:function(){this.checkMetricTypeChanged(),this.resetChart(),this.resetDetailBy();var a=e.get("selectedMetric");b.isEmpty(a)?(this.resetOrgs(),this.resetFilters()):(f.clear(),this.requestOrgsFilters(),this.setCurrentMetric(a))},checkMetricTypeChanged:function(){var a=e.getMetricType(this.currentMetric),c=e.getMetricType(e.get("selectedMetric"));b.isEqual(a,c)||this.resetFilters()},setCurrentMetric:function(a){this.currentMetric=a},orgChanged:function(){this.resetChart();var a=f.get("selectedOrg");b.isEmpty(a)?this.resetDetailBy():(a==B.GROUP_BY_CLIENT_LOCATION?this.requestDetailBy():this.resetDetailBy(),this.requestStatisticsHandler())},detailByChanged:function(){this.requestStatisticsHandler()},renderMetrics:function(){var a=new n({model:e});this.$metricSelect.html(a.render().$el)},renderOrgs:function(){var a=new o({model:f});this.$orgsSelect.html(a.render().$el)},renderFiltersSelector:function(){var a=new q({model:i,collection:m});this.$filterSelector.html(a.render().$el)},renderDetailBy:function(){var a=new p({model:g});this.$detailBySelect.html(a.render().$el)},renderFilter:function(a,c){var d=c.get("filterID"),f=e.getMetricType(e.get("selectedMetric")),g=new j({filterID:d,metricType:f}),h=this;g.fetch({success:function(){c.set("title",g.get("title"));var d=new a({model:c});h.$filters.append(d.render().$el),b.isUndefined(d.renderMap)||d.renderMap()}})},resetRequest:function(){b.isUndefined(this.requestStats)||this.requestStats.abort(),this.$loading.hide()},resetAnalysis:function(){this.resetRequest(),this.resetOrgs(),this.resetFilters(),this.resetDetailBy(),this.resetChart(),e.clear(),this.requestMetrics()},resetMetrics:function(){e.clear(),this.renderMetrics()},resetOrgs:function(){f.clear(),this.renderOrgs()},resetFilters:function(){i.clear(),this.renderFiltersSelector(),m.reset(),this.$filters.html(""),this.$applyButton.hide()},resetDetailBy:function(){g.clear(),this.$detailBySelect.html("")},resetChart:function(){h.clear(),b.isUndefined(this.chart)||b.isUndefined(this.chart.remove)||this.chart.remove()},addFilterHandler:function(a){var c=a.attributes.filterID,d=z.getFilterUrl(c),e=z.getFilterView(c);this.checkCollection(),b.isUndefined(e)||(b.isUndefined(d)?this.renderFilter(e,a):(a.set({url:d}),this.addRequestFilter(e,a)))},addRequestFilter:function(a,c){var d=this;b.isUndefined(c.getTerms)?c.fetch({success:function(){d.renderFilter(a,c)}}):c.getTerms().then(function(e){var f=[];b.each(e.elements,function(a){f.push({id:a.id,name:a.categoryTermName})}),c.set("content",f),d.renderFilter(a,c)})},renderChartHandler:function(a,c,d,e,f){this.resetChart();var g=[],h=[],i=this;c=parseInt(c),A.getIDModel(c).then(function(c){c=b.isUndefined(c)||b.isUndefined(c.elements)?c:c.elements,b.each(e,function(a,d){if(b.isUndefined(c))g.push(a.group),h.push(a.data);else{for(var f=b.isUndefined(a.group.groupName)?a.group:a.group.groupName,i=void 0,j=0;j<c.length;j++){var k=c[j];if(k.id==f){i=b.isUndefined(k.name)?k:k.name;break}}i=b.isUndefined(i)?f:i,b.isUndefined(e[d].group.groupName)?e[d].group=i:e[d].group.groupName=i,g.push(i),h.push(a.data)}}),i.renderChart(a,d,e,f,g,h)})},renderChart:function(a,c,d,e,f,h){switch(c){case B.BAR_CHART:this.chart=new v({groups:f,data:h});break;case B.HEAT_MAP:this.chart=new x({chartData:d});break;case B.DATE_CHART:this.chart=new w({groups:f,data:h});break;case B.COUNTRY_BAR_CHART:this.chart=new v({groups:f,data:h}),b.isUndefined(e)&&(g.set({countryIDs:f}),this.requestDetailBy());break;case B.MAP_EXPLORED_ZONES:this.chart=new y({chartData:d})}b.isUndefined(this.chart)?console.log("Chart "+c+" not found"):this.chart.render()},requestMetrics:function(){var a,c=new Date,d=window.localStorage.getItem("metricsDate");if(b.isNull(d)||(d=new Date(d),a=c.getTime()-d.getTime()),!b.isUndefined(a)&&a<B.METRICS_CACHE_EXPIRATION){var f=JSON.parse(window.localStorage.getItem("metrics")),g=JSON.parse(window.localStorage.getItem("filterMetrics"));e.set("metrics",f),e.set("filterMetrics",g),this.renderMetrics()}else{var h=this;e.fetch({success:function(){var a=new k;a.getTerms().then(function(a){e.set("filterMetrics",a.elements),window.localStorage.setItem("metrics",JSON.stringify(e.get("metrics"))),window.localStorage.setItem("filterMetrics",JSON.stringify(e.get("filterMetrics"))),window.localStorage.setItem("metricsDate",new Date),h.renderMetrics()})},error:function(){h.resetMetrics(),h.resetChart()}})}},requestOrgsFilters:function(){var a=e.get("selectedMetric");if(b.isEmpty(a))this.resetOrgs(),this.resetFilters(),this.resetChart();else{var c=this;f.set("metricID",a),f.fetch({success:function(){c.renderOrgs()},error:function(){c.resetOrgs(),c.resetChart()}}),i.set("metricID",a),i.fetch({success:function(){c.renderFiltersSelector()},error:function(){c.resetFilters(),c.resetChart()}})}},requestDetailBy:function(){var a=this;g.requestCountries({success:function(b){g.set("countries",b),a.renderDetailBy()},error:function(){a.resetDetailBy()}})},requestStatisticsHandler:function(){var a=e.get("selectedMetric"),c=e.get("selectedFilterID"),d=f.get("selectedOrg"),h=[],i=g.get("selectedDetailBy");if(!(b.isUndefined(a)||b.isEmpty(a)||b.isUndefined(d)||b.isEmpty(d))){this.resetRequest(),this.resetChart(),b.isUndefined(c)||z.processMetricFilters(h,c);var j=this;m.each(function(a){switch(a.attributes.filterID){case B.FILTER_DATE_RANGE:z.processDateFilters(h,a,j.$("#fromDate").val(),j.$("#toDate").val());break;default:z.processFilter(h,a)}}),this.requestStatistics(a,d,h,i)}},requestStatistics:function(a,c,d,e){var f={metricID:a,groupByID:c,filters:d};b.isUndefined(e)||b.isEmpty(e)||(f.countryID=e),h.clear().set(f),this.$loading.show();var g=this;this.requestStats=h.requestStats({success:function(a){g.$loading.hide(),g.renderChartHandler(f.metricID,f.groupByID,a.chartType,a.data,a.countryID)},error:function(){g.$loading.hide(),g.resetChart()}})}});return C});
