<?php
Cogumelo::load('coreView/View.php');
geozzy::load('controller/ResourceController.php');



class GeozzyResourceView extends View
{

  private $defResCtrl = null;

  public function __construct( $baseDir = false ){
    parent::__construct( $baseDir );

    common::autoIncludes();
    form::autoIncludes();
    user::autoIncludes();
    filedata::autoIncludes();

    $this->defResCtrl = new ResourceController();
  }

  /**
    Evaluate the access conditions and report if can continue
   *
   * @return bool : true -> Access allowed
   **/
  public function accessCheck() {

    return true;
  }



  /**
    Defino un formulario con su TPL como Bloque
  */
  public function getFormBlock( $formName, $urlAction, $valuesArray = false ) {

    return( $this->defResCtrl->getFormBlock( $formName, $urlAction, $valuesArray ) );
  } // function getFormBlock()



  /**
    Proceso formulario
  */
  public function actionResourceForm() {
    error_log( "GeozzyResourceView: actionResourceForm()" );

    $this->defResCtrl->actionResourceForm();
  } // function actionResourceForm()


} // class ResourceView extends Vie
