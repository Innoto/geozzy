<?php
Cogumelo::load( 'coreController/Module.php' );


class rextAppEspazoNatural extends Module {

  public $name = 'rextAppEspazoNatural';
  public $version = '1.0';


  public $models = array();

  public $taxonomies = array(
    'rextAppEspazoNaturalType' => array(
      'idName' => 'rextAppEspazoNaturalType',
      'name' => array(
        'en' => 'EspazoNatural Type',
        'es' => 'Tipo de Espacio natural',
        'gl' => 'Tipo de Espazo natural'
      ),
      'editable' => 1,
      'nestable' => 0,
      'sortable' => 1,
      'initialTerms' => array(



        array(
          'idName' => 'montanas',
          'icon' => 'view/categoryIcons/montanas.svg',
          'name' => array(
            'en' => 'Mountains',
            'es' => 'Montañas',
            'gl' => 'Montañas'
          )
        ),

        array(
          'idName' => 'fragas',
          'icon' => 'view/categoryIcons/bosques.svg',
          'name' => array(
            'en' => 'Forests',
            'es' => 'Bosques',
            'gl' => 'Fragas'
          )
        ),

        array(
          'idName' => 'praias',
          'icon' => 'view/categoryIcons/playas.svg',
          'name' => array(
            'en' => 'Beaches and Sandunes',
            'es' => 'Playas, calas y dunas',
            'gl' => 'Praias, calas e dunas'
          )
        ),

        array(
          'idName' => 'rios',
          'icon' => 'view/categoryIcons/rios.svg',
          'name' => array(
            'en' => 'Rivers and Lakes',
            'es' => 'Rios y lagunas',
            'gl' => 'Ríos e lagoas'
          )
        ),

        array(
          'idName' => 'fervenzas',
          'icon' => 'view/categoryIcons/cascadas.svg',
          'name' => array(
            'en' => 'Waterfalls and Ponds',
            'es' => 'Cascadas y charcas',
            'gl' => 'Fervenzas e pozas'
          )
        ),

        array(
          'idName' => 'cantis',
          'icon' => 'view/categoryIcons/acantilados.svg',
          'name' => array(
            'en' => 'Cliffs, Canyons and Capes',
            'es' => 'Acantilados, cañones y cabos',
            'gl' => 'Cantís, cañóns e cabos'
          )
        ),

        array(
          'idName' => 'humidas',
          'icon' => 'view/categoryIcons/humedales.svg',
          'name' => array(
            'en' => 'Wetlands',
            'es' => 'Humedales',
            'gl' => 'Zoas húmidas'
          )
        ),

        array(
          'idName' => 'illas',
          'icon' => 'view/categoryIcons/islas.svg',
          'name' => array(
            'en' => 'Islands',
            'es' => 'Islas',
            'gl' => 'Illas'
          )
        )


      )
    )
  );

  public $dependences = array();

  public $includesCommon = array(
    'controller/RExtAppEspazoNaturalController.php'
    //,'model/RExtUrlModel.php'
  );


  public function __construct() {
  }


  public function moduleRc() {
    geozzy::load('controller/RTUtilsController.php');
    RTUtilsController::rExtModuleRc( __CLASS__ );
  }
}
