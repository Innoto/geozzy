<?php

global $GEOZZY_EXPLORERS;


$GEOZZY_EXPLORERS = array(

  'finalistas' => array(
    'module' => 'appExplorer',
    'controllerFile' => 'controller/innParticipaExplorerController.php',
    'controllerName' => 'innParticipaExplorerController',
    'name' => __('Finalistas'),
    'mapBounds' => array(
      array(43.87,-9.35),
      array(41.78,-6.73)
    ),
    'filters' => array(
      'catGroupIdNames' => array()
    )
  )
);
