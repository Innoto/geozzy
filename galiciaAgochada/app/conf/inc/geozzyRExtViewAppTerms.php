<?php

/*
  EXAMPLE tpl:
  array(
    'idName' => 'tplApp{CCCCCC}',
    'tpl' => 'rExtViewAltAppCCCCCC.tpl', // rExtViewAltApp{CCCCCC}.tpl si no se indica
    'module' => 'rextView', // rextView si no se indica, false para buscar en App
    'name' => array(
      'en' => 'Use CCCCCC tpl',
      'es' => 'Usar el tpl CCCCCC',
      'gl' => 'Usar o tpl CCCCCC'
    )
  )

  EXAMPLE class:
  array(
    'idName' => 'viewApp{CCCCCC}',
    'view' => 'RExtViewAltAppCCCCCC', // RExtViewAltApp{CCCCCC} si no se indica
    'module' => 'rextView', // rextView si no se indica, false para buscar en App
    // Class: RExtViewAltAppCCCCCC, File: RExtViewAltAppCCCCCC.php
    'name' => array(
      'en' => 'Use CCCCCC class',
      'es' => 'Usar la clase CCCCCC',
      'gl' => 'Usar a clase CCCCCC'
    )
  )
*/


$rExtViewAppInitialTerms = array();

$rExtViewAppInitialTerms[] = array(
  'idName' => 'viewAppHome',
  // Class: RExtViewAltAppHome, File: RExtViewAltAppHome.php
  'name' => array(
    'en' => 'Use Home',
    'es' => 'Usar Portada',
    'gl' => 'Usar Portada'
  )
);

$rExtViewAppInitialTerms[] = array(
  'idName' => 'viewAppXantaresExplorer',
  // Class: RExtViewAltAppXantaresExplorer, File: RExtViewAltAppXantaresExplorer.php
  'name' => array(
    'en' => 'Use Xantares Explorer',
    'es' => 'Usar Explorador de Xantares',
    'gl' => 'Usar Explorador de Xantares'
  )
);

$rExtViewAppInitialTerms[] = array(
  'idName' => 'viewAppAloxamentosExplorer',
  // Class: RExtViewAltAppAloxamentosExplorer, File: RExtViewAltAppAloxamentosExplorer.php
  'name' => array(
    'en' => 'Use Aloxamentos Explorer',
    'es' => 'Usar Explorador de Aloxamentos',
    'gl' => 'Usar Explorador de Aloxamentos'
  )
);

$rExtViewAppInitialTerms[] = array(
  'idName' => 'viewAppRinconsExplorer',
  // Class: RExtViewAltAppRinconsExplorer, File: RExtViewAltAppRinconsExplorer.php
  'name' => array(
    'en' => 'Use Rincons Explorer',
    'es' => 'Usar Explorador de Rincons',
    'gl' => 'Usar Explorador de Rincons'
  )
);

$rExtViewAppInitialTerms[] = array(
  'idName' => 'viewAppPraiasExplorer',
  // Class: RExtViewAltAppPraiasExplorer, File: RExtViewAltAppPraiasExplorer.php
  'name' => array(
    'en' => 'Use Praias Explorer',
    'es' => 'Usar Explorador de Praias',
    'gl' => 'Usar Explorador de Praias'
  )
);

$rExtViewAppInitialTerms[] = array(
  'idName' => 'viewAppPaisaxesExplorer',
  // Class: RExtViewAltAppPaisaxesExplorer, File: RExtViewAltAppPaisaxesExplorer.php
  'name' => array(
    'en' => 'Use Paisaxes Explorer',
    'es' => 'Usar Explorador de Paisaxes',
    'gl' => 'Usar Explorador de Paisaxes'
  )
);

$rExtViewAppInitialTerms[] = array(
  'idName' => 'viewAppTodosSegredosExplorer',
  // Class: RExtViewAltAppTodosSegredosExplorer, File: RExtViewAltAppTodosSegredosExplorer.php
  'name' => array(
    'en' => 'Use TodosSegredos Explorer',
    'es' => 'Usar Explorador de TodosSegredos',
    'gl' => 'Usar Explorador de TodosSegredos'
  )
);


