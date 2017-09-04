<?php
Cogumelo::load( 'coreController/Module.php' );


class rextEventCollection extends Module {

  public $name = 'rextEventCollection';
  public $version = '1.0';


  public $models = array();

  public $taxonomies = array(
    'rextEventCollectionView' => array(
      'idName' => 'rextEventCollectionView',
      'name' => array(
        'en' => 'EventCollection view',
        'es' => 'Vista de colección de eventos',
        'gl' => 'Vista de colección de eventos'
      ),
      'editable' => 0,
      'nestable' => 0,
      'sortable' => 0,
      'initialTerms' => array(
        'basicView' => array(
          'idName' => 'basicView',
          'name' => array(
            'en' => 'Basic view',
            'es' => 'Vista básica',
            'gl' => 'Vista básica'
          )
        ),
        'calendarView' => array(
          'idName' => 'calendarView',
          'name' => array(
            'en' => 'Calendar view',
            'es' => 'Vista de calendario',
            'gl' => 'Vista de calendario'
          )
        ),
        'listView' => array(
          'idName' => 'listView',
          'name' => array(
            'en' => 'List view',
            'es' => 'Vista de lista',
            'gl' => 'Vista de lista'
          )
        )
      )
    ),
    'rextEventCollectionFilter' => array(
      'idName' => 'rextEventCollectionFilter',
      'name' => array(
        'en' => 'Event Collection filter',
        'es' => 'Filtro de colección de eventos',
        'gl' => 'Vista de colección de eventos'
      ),
      'editable' => 0,
      'nestable' => 0,
      'sortable' => 0,
      'initialTerms' => array(
        'allEvents' => array(
          'idName' => 'allEvents',
          'name' => array(
            'en' => 'All events',
            'es' => 'Todos los eventos',
            'gl' => 'Todos os eventos'
          )
        ),
        'nextEvents' => array(
          'idName' => 'nextEvents',
          'name' => array(
            'en' => 'Only next events from today',
            'es' => 'Sólo eventos a partir de hoy',
            'gl' => 'Só eventos a partir de hoxe'
          )
        )
      )
    )
  );

  public $dependences = array();

  public $includesCommon = array(
    'controller/RExtEventCollectionController.php',
    'js/rextEventCollection.js',
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
