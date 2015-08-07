<?php
Cogumelo::load( 'coreController/Module.php' );


class rextView extends Module {

  public $name = 'rextView';
  public $version = '1.0';


  public $models = array(
    // 'RExtViewModel'
  );

  public $taxonomies = array(
    'viewAlternativeMode' => array(
      'idName' => 'viewAlternativeMode',
      'name' => array(
        'en' => 'viewAlternativeMode',
        'es' => 'viewAlternativeMode',
        'gl' => 'viewAlternativeMode'
      ),
      'editable' => 0,
      'nestable' => 0,
      'sortable' => 0,
      'initialTerms' => array(
        array(
          'idName' => 'none',
          'name' => array(
            'en' => 'none',
            'es' => 'none',
            'gl' => 'none'
          )
        ),
        array(
          'idName' => 'tplSimplePage',
          'name' => array(
            'en' => 'tplSimplePage',
            'es' => 'tplSimplePage',
            'gl' => 'tplSimplePage'
          )
        ),
        array(
          'idName' => 'tplTwoColsPage',
          'name' => array(
            'en' => 'tplTwoColsPage',
            'es' => 'tplTwoColsPage',
            'gl' => 'tplTwoColsPage'
          )
        ),
        array(
          'idName' => 'viewLandingHotelCollections',
          'name' => array(
            'en' => 'viewLandingHotelCollections',
            'es' => 'viewLandingHotelCollections',
            'gl' => 'viewLandingHotelCollections'
          )
        ),
        array(
          'idName' => 'viewLandingExplorerFull',
          'name' => array(
            'en' => 'viewLandingExplorerFull',
            'es' => 'viewLandingExplorerFull',
            'gl' => 'viewLandingExplorerFull'
          )
        )
      )
    )
  );

  public $dependences = array();

  public $includesCommon = array(
    'controller/RExtViewController.php'
    //,'model/RExtViewModel.php'
  );


  public function __construct() {

  }

}
