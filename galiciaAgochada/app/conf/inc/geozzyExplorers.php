<?php

global $GEOZZY_EXPLORERS;


$GEOZZY_EXPLORERS = array(
/*
  'default' => array(
    'module' => 'explorer',
    'controllerFile' => 'controller/DefaultExplorerController.php',
    'controllerName' => 'DefaultExplorerController',
    'name' => 'Default',
    'mapBounds' => array(
      array( 49.9, -9.8 ),
      array( 41.7, -6.6 )
    ),
    'filters' => array(
      'catGroupIdNames' => array()
    )
  ),
*/
  'paisaxes' => array(
    'module' => 'appExplorer',
    'controllerFile' => 'controller/PaisaxesExplorerController.php',
    'controllerName' => 'PaisaxesExplorerController',
    'name' => __('Paisaxes Espectaculares'),
    'mapBounds' => array(
      array(43.87,-9.35),
      array(41.78,-6.73)
    ),
    'filters' => array(
      'catGroupIdNames' => array( 'rextAppEspazoNaturalType', 'rextAppZonaType' )
    )
  ),
  'xantares' => array(
    'module' => 'appExplorer',
    'controllerFile' => 'controller/XantaresExplorerController.php',
    'controllerName' => 'XantaresExplorerController',
    'name' => __('Sabrosos xantares'),
    'mapBounds' => array(
      array(43.87,-9.35),
      array(41.78,-6.73)
    ),
    'filters' => array(
      'catGroupIdNames' => array( 'eatanddrinkSpecialities', 'rextAppZonaType' )
    )
  ),
  'todosSegredos' => array(
    'module' => 'appExplorer',
    'controllerFile' => 'controller/TodosSegredosExplorerController.php',
    'controllerName' => 'TodosSegredosExplorerController',
    'name' => __('Descúbreos todos xuntos'),
    'mapBounds' => array(
      array(43.87,-9.35),
      array(41.78,-6.73)
    ),
    'filters' => array(
      'catGroupIdNames' => array( 'eatanddrinkSpecialities', 'rextAppZonaType' )
    )
  ),
  'aloxamentos' => array(
    'module' => 'appExplorer',
    'controllerFile' => 'controller/AloxamentosExplorerController.php',
    'controllerName' => 'AloxamentosExplorerController',
    'name' => __('Aloxamentos con encanto'),
    'mapBounds' => array(
      array(43.87,-9.35),
      array(41.78,-6.73)
    ),
    'filters' => array(
      'catGroupIdNames' => array( 'accommodationType', 'accommodationCategory',
        'accommodationServices', 'accommodationFacilities', 'rextAppZonaType'
      )
    )
  ),
  'praias' => array(
    'module' => 'appExplorer',
    'controllerFile' => 'controller/PraiasExplorerController.php',
    'controllerName' => 'PraiasExplorerController',
    'name' => __('Praias de ensono'),
    'mapBounds' => array(
      array(43.87,-9.35),
      array(41.78,-6.73)
    ),
    'filters' => array(
      'catGroupIdNames' => array()
    )
  ),
  'rincons' => array(
    'module' => 'appExplorer',
    'controllerFile' => 'controller/RinconsExplorerController.php',
    'controllerName' => 'RinconsExplorerController',
    'name' => __('Rincóns con encanto'),
    'mapBounds' => array(
      array(43.87,-9.35),
      array(41.78,-6.73)
    ),
    'filters' => array(
      'catGroupIdNames' => array( 'rextAppLugarType', 'rextAppZonaType' )
    )
  ),
  'pois' => array(
    'module' => 'rextPoiCollection',
    'controllerFile' => 'controller/PoisExplorerController.php',
    'controllerName' => 'PoisExplorerController',
    'name' => __('Puntos de interese')
  ),
  'rutas' => array(
    'module' => 'appExplorer',
    'controllerFile' => 'controller/RutasExplorerController.php',
    'controllerName' => 'RutasExplorerController',
    'name' => __('Rutas')
  )
);
