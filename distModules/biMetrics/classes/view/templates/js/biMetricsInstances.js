var geozzy = geozzy || {};

if(!geozzy.biMetricsInstances) geozzy.biMetricsInstances={};


if(typeof geozzy.biMetricsInstances.explorer  === 'undefined'){
  geozzy.biMetricsInstances.explorer = new geozzy.biMetricsComponents.explorer();
}


if(typeof geozzy.biMetricsInstances.resource  === 'undefined'){
  geozzy.biMetricsInstances.resource = new geozzy.biMetricsComponents.resource();
}
