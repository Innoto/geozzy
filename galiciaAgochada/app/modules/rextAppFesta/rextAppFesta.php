<?php
Cogumelo::load( 'coreController/Module.php' );


class rextAppFesta extends Module {

  public $name = 'rextAppFesta';
  public $version = '1.0';


  public $models = array();

  public $taxonomies = array(
    'rextAppFestaType' => array(
      'idName' => 'rextAppFestaType',
      'name' => array(
        'en' => 'Party type',
        'es' => 'Tipo de Fiesta',
        'gl' => 'Tipo de Festa'
      ),
      'editable' => 1,
      'nestable' => 0,
      'sortable' => 1,
      'initialTerms' => array(
        array(
          'idName' => 'festapopular',
          'icon' => 'view/categoryIcons/festapopular.svg',
          'name' => array(
            'en' => 'Popular party',
            'es' => 'Fiesta popular',
            'gl' => 'Festa popular'
          )
        ),
        array(
          'idName' => 'feria',
          'icon' => 'view/categoryIcons/feria.svg',
          'name' => array(
            'en' => 'Feria',
            'es' => 'Feria',
            'gl' => 'Feria'
          )
        )
      )
    )
  );

  public $dependences = array();

  public $includesCommon = array(
    'controller/RExtAppFestaController.php'
  );


  public function __construct() {
  }


  public function moduleRc() {
    geozzy::load('controller/RTUtilsController.php');

    $rtUtilsControl = new RTUtilsController(__CLASS__);
    $rtUtilsControl->rExtModuleRc();
  }
}
