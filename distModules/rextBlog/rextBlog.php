<?php
Cogumelo::load( 'coreController/Module.php' );


class rextBlog extends Module {

  public $name = 'rextBlog';
  public $version = 1.0;


  public $models = array();

  public $taxonomies = array(
    'blogLabel' => array(
      'idName' => 'blogLabel',
      'name' => array(
        'en' => 'Blog label',
        'es' => 'Etiqueta Blog',
        'gl' => 'Etiqueta Blogue'
      ),
      'editable' => 1,
      'nestable' => 1,
      'sortable' => 1,
      // 'initialTerms' => array()
    )
  );

  public $dependences = array();

  public $includesCommon = array(
    'controller/RExtBlogController.php'
  );


  public function __construct() {
  }


  public function moduleRc() {
    geozzy::load('controller/RTUtilsController.php');

    $rtUtilsControl = new RTUtilsController(__CLASS__);
    $rtUtilsControl->rExtModuleRc();
  }
}
