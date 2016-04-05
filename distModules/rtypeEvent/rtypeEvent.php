<?php
Cogumelo::load( 'coreController/Module.php' );


class rtypeEvent extends Module {

  public $name = 'rtypeEvent';
  public $version = 1.0;
  public $rext = array( 'rextEvent' );

  public $dependences = array();

  public $includesCommon = array(
    'controller/RTypeEventController.php'
    //'view/RTypeEventView.php'
  );

  public $nameLocations = array(
    'es' => 'Evento',
    'en' => 'Event',
    'gl' => 'Event'
  );


  public function __construct() {
    $this->addUrlPatterns( '#^rtypeEvent/event/create$#', 'view:RTypeEventView::createForm' );
    $this->addUrlPatterns( '#^rtypeEvent/event/edit/(\d+)$#', 'view:RTypeEventView::editForm' );
    $this->addUrlPatterns( '#^rtypeEvent/event/sendevent$#', 'view:RTypeEventView::sendForm' );
  }


  public function moduleRc() {
    geozzy::load('controller/RTUtilsController.php');


    $rtUtilsControl = new RTUtilsController(__CLASS__);
    $rtUtilsControl->rTypeModuleRc();
  }
}
