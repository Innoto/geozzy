<?php
Cogumelo::load( 'coreController/Module.php' );


class rextAppLugar extends Module {

  public $name = 'rextAppLugar';
  public $version = '1.0';


  public $models = array();

  public $taxonomies = array(
    'rextAppLugarType' => array(
      'idName' => 'rextAppLugarType',
      'name' => array(
        'en' => 'Lugar type',
        'es' => 'Tipo de Lugar',
        'gl' => 'Tipo de Lugar'
      ),
      'editable' => 1,
      'nestable' => 0,
      'sortable' => 1,
      'initialTerms' => array(
        array(
          'idName' => 'rinconesurbanos',
          'icon' => 'view/categoryIcons/rinconesurbanos.svg',
          'name' => array(
            'en' => 'Urban places',
            'es' => 'Rincones urbanos',
            'gl' => 'Rincóns urbanos'
          )
        ),
        array(
          'idName' => 'rinconesrurales',
          'icon' => 'view/categoryIcons/rinconesrurales.svg',
          'name' => array(
            'en' => 'Rural places',
            'es' => 'Rincones rurales',
            'gl' => 'rincóns rurais'
          )
        ),
        array(
          'idName' => 'rinconeshistoricos',
          'icon' => 'view/categoryIcons/rinconeshistoricos.svg',
          'name' => array(
            'en' => 'Historical places',
            'es' => 'Rincones históricos',
            'gl' => 'rincóns históricos'
          )
        )
      )
    )
  );

  public $dependences = array();

  public $includesCommon = array(
    'controller/RExtAppLugarController.php'
    //,'model/RExtUrlModel.php'
  );


  public function __construct() {
  }


  public function moduleRc() {
    geozzy::load('controller/RTUtilsController.php');

    $rtUtilsControl = new RTUtilsController(__CLASS__);
    $rtUtilsControl->rExtModuleRc();
  }
}
