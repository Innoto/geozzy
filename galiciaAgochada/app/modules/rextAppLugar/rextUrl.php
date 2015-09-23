<?php
Cogumelo::load( 'coreController/Module.php' );


class rextUrl extends Module {

  public $name = 'rextUrl';
  public $version = '1.0';


  public $models = array(
    'RExtUrlModel'
  );

  public $taxonomies = array(
    'urlContentType' => array(
      'idName' => 'urlContentType',
      'name' => array(
        'en' => 'Content type',
        'es' => 'Tipo de contenido',
        'gl' => 'Tipo de contido'
      ),
      'editable' => 0,
      'nestable' => 0,
      'sortable' => 0,
      'initialTerms' => array(
        array(
          'idName' => 'page',
          'name' => array(
            'en' => 'Page',
            'es' => 'Página',
            'gl' => 'Páxina'
          )
        ),
        array(
          'idName' => 'file',
          'name' => array(
            'en' => 'File',
            'es' => 'Fichero',
            'gl' => 'Ficheiro'
          )
        ),
        array(
          'idName' => 'media',
          'name' => array(
            'en' => 'Media',
            'es' => 'Media',
            'gl' => 'Media'
          )
        ),
        array(
          'idName' => 'image',
          'name' => array(
            'en' => 'Image',
            'es' => 'Imagen',
            'gl' => 'Imaxe'
          )
        ),
        array(
          'idName' => 'audio',
          'name' => array(
            'en' => 'Audio',
            'es' => 'Audio',
            'gl' => 'Audio'
          )
        ),
        array(
          'idName' => 'video',
          'name' => array(
            'en' => 'Video',
            'es' => 'Video',
            'gl' => 'Video'
          )
        )
      )
    )
  );

  public $dependences = array();

  public $includesCommon = array(
    'controller/RExtUrlController.php'
    //,'model/RExtUrlModel.php'
  );


  public function __construct() {
  }


  public function moduleRc() {
    geozzy::load('controller/ResourcetypeController.php');
    ResourcetypeController::rExtModuleRc( __CLASS__ );
  }
}
