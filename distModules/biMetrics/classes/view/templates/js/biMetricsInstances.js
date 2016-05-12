var geozzy = geozzy || {};

if(!geozzy.biMetricsInstances) geozzy.biMetricsInstances={};


if(typeof geozzy.biMetricsInstances.configuration  === 'undefined'){
  geozzy.biMetricsInstances.configuration = new geozzy.biMetricsComponents.biConfiguration();
}


if(typeof geozzy.biMetricsInstances.explorer  === 'undefined'){
  geozzy.biMetricsInstances.explorer = new geozzy.biMetricsComponents.explorer();
}


if(typeof geozzy.biMetricsInstances.resource  === 'undefined'){
  geozzy.biMetricsInstances.resource = new geozzy.biMetricsComponents.resource();
}


if(typeof geozzy.biMetricsComponents.recommender  === 'undefined'){
  geozzy.biMetricsInstances.recommender = new geozzy.biMetricsComponents.biRecommender();
}
