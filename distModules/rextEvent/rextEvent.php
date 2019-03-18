<?php
Cogumelo::load( 'coreController/Module.php' );


class rextEvent extends Module {

  public $name = 'rextEvent';
  public $version = '3';


  public $models = array(
    'EventModel'
  );

  public $taxonomies = array(
    'rextEventType' => array(
      'idName' => 'rextEventType',
      'name' => array(
        'en' => 'Event type',
        'es' => 'Tipo de evento',
        'gl' => 'Tipo de evento'
      ),
      'editable' => 1,
      'nestable' => 0,
      'sortable' => 1,
      'initialTerms' => array(
        array(
          'idName' => 'concerto',
          'icon' => 'view/categoryIcons/concerto.svg',
          'name' => array(
            'en' => 'Live music',
            'es' => 'Conciertos',
            'gl' => 'Concertos'
          )
        ),
        array(
          'idName' => 'festapopular',
          'icon' => 'view/categoryIcons/festa.svg',
          'name' => array(
            'en' => 'Popular party',
            'es' => 'Fiesta popular',
            'gl' => 'Festa popular'
          )
        ),
        array(
          'idName' => 'conferencia',
          'icon' => 'view/categoryIcons/conferencia.svg',
          'name' => array(
            'en' => 'Conference',
            'es' => 'Conferencia',
            'gl' => 'Conferencia'
          )
        ),
        array(
          'idName' => 'exposicion',
          'icon' => 'view/categoryIcons/expo.svg',
          'name' => array(
            'en' => 'Exposition',
            'es' => 'Explosición',
            'gl' => 'Exposición'
          )
        )
      )
    )
  );

  public $dependences = array();

  public $includesCommon = array(
    'controller/RExtEventController.php',
    'model/EventModel.php'
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
