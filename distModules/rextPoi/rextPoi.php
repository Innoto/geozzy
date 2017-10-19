<?php
Cogumelo::load( 'coreController/Module.php' );


class rextPoi extends Module {

  public $name = 'rextPoi';
  public $version = '1.0';


  public $models = array(
  );

  public $taxonomies = array(
    'rextPoiType' => array(
      'idName' => 'rextPoiType',
      'name' => array(
        'en' => 'Poi type',
        'es' => 'Tipo de Punto de interés',
        'gl' => 'Tipo de Punto de interés'
      ),
      'editable' => 1,
      'nestable' => 0,
      'sortable' => 1,
      'initialTerms' => array(
        array(
          'idName' => 'mirador',
          'icon' => 'view/categoryIcons/mirador.svg',
          'name' => array(
            'en' => 'Lookout',
            'es' => 'Mirador',
            'gl' => 'Mirador'
          )
        ),
        array(
          'idName' => 'acceso',
          'icon' => 'view/categoryIcons/acceso.svg',
          'name' => array(
            'en' => 'Access point',
            'es' => 'Punto de acceso',
            'gl' => 'Punto de acceso'
          )
        ),
        array(
          'idName' => 'foto',
          'icon' => 'view/categoryIcons/foto.svg',
          'name' => array(
            'en' => 'Photo',
            'es' => 'Foto',
            'gl' => 'Foto'
          )
        ),
        array(
          'idName' => 'puntoPanorama',
          'icon' => 'view/categoryIcons/panorama.svg',
          'name' => array(
            'en' => 'Panaroma point',
            'es' => 'Punto panorama',
            'gl' => 'Punto panorama'
          )
        )
      )
    )
  );

  public $dependences = array();

  public $includesCommon = array(
    'controller/RExtPoiController.php'
  );


  public function __construct() {
  }


  public function moduleRc() {
    geozzy::load('controller/RTUtilsController.php');

    $rtUtilsControl = new RTUtilsController(__CLASS__);
    $rtUtilsControl->rExtModuleRc();
  }

  public function moduleDeploy() {
    geozzy::load('controller/RTUtilsController.php');

    $rtUtilsControl = new RTUtilsController(__CLASS__);
    $rtUtilsControl->rExtModuleDeploy();
  }
}
