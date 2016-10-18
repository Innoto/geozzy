<?php
Cogumelo::load('coreView/View.php');
rtypeCommunity::load('controller/RTypeCommunityController.php');



class RTypeCommunityView extends View {

  private $defResCtrl = null;
  private $rTypeCtrl = null;

  public function __construct( $defResCtrl = null ){
    parent::__construct( $baseDir );

    $this->defResCtrl = $defResCtrl;
    $this->rTypeCtrl = new RTypeCommunityController( $defResCtrl );
  }



  /**
    Defino un formulario con su TPL como Bloque
   */
  public function getFormBlock( $formName, $urlAction, $valuesArray = false ) {
    // error_log( "RTypeCommunityView: getFormBlock()" );
    return( false );
  } // function getFormBlock()



  /**
    Proceso formulario
   */
  public function actionResourceForm() {
    // error_log( "RTypeCommunityView: actionResourceForm()" );

  } // function actionResourceForm()


} // class RTypeCommunityView
