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
    Defino un formulario
  */
  public function getFormObj( $formName, $urlAction, $valuesArray = false ) {
    error_log( "GeozzyResourceView: getFormObj()" );

    return $this->defResCtrl->getFormObj( $formName, $urlAction, $valuesArray );
  } // function getFormObj()


  /**
    Defino un formulario con su TPL como Bloque
  */
  public function getFormBlock( $formName, $urlAction, $valuesArray = false ) {
    error_log( "GeozzyResourceView: getFormBlock()" );

    $form = $this->getFormObj( $formName, $urlAction, $valuesArray );

    $this->template->assign( 'formOpen', $form->getHtmpOpen() );

    $this->template->assign( 'formFieldsArray', $form->getHtmlFieldsArray() );

    $this->template->assign( 'formFields', $form->getHtmlFieldsAndGroups() );

    $this->template->assign( 'formClose', $form->getHtmlClose() );
    $this->template->assign( 'formValidations', $form->getScriptCode() );

    $this->template->setTpl( 'resourceFormBlock.tpl', 'geozzy' );

    return( $this->template );
  } // function getFormBlock()



  /**
    Proceso formulario
  */
  public function actionResourceForm() {
    error_log( "GeozzyResourceView: actionResourceForm()" );

    $this->defResCtrl->actionResourceForm();
  } // function actionResourceForm()


} // class ResourceView extends Vie
