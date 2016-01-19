<?php

global $GEOZZY_EXPLORERS;


$GEOZZY_EXPLORERS = array(
  'default' => array(
    'module' => 'explorer',
    'controllerFile' => 'controller/DefaultExplorerController.php',
    'controllerName' => 'DefaultExplorerController'
  ),
  'paisaxes' => array(
    'module' => 'explorer',
    'controllerFile' => 'controller/PaisaxesExplorerController.php',
    'controllerName' => 'PaisaxesExplorerController'
  ),
  'xantares' => array(
    'module' => 'explorer',
    'controllerFile' => 'controller/XantaresExplorerController.php',
    'controllerName' => 'XantaresExplorerController'
  ),
  'todosSegredos' => array(
    'module' => 'explorer',
    'controllerFile' => 'controller/TodosSegredosExplorerController.php',
    'controllerName' => 'TodosSegredosExplorerController'
  )
);
