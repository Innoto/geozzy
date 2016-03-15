var geozzy = geozzy || {};

if(!geozzy.biMetricsInstances) geozzy.biMetricsInstances={};

geozzy.biMetricsInstances.explorer = new geozzy.biMetrics.controller.explorer();
geozzy.biMetricsInstances.resource = new geozzy.biMetrics.controller.resource();
