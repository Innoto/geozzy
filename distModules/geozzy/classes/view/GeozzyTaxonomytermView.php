<?php
Cogumelo::load('coreView/View.php');

common::autoIncludes();
form::autoIncludes();
filedata::autoIncludes();
user::autoIncludes();


class GeozzyTaxonomytermView extends View
{


  public function __construct( $baseDir = false ){
    parent::__construct( $baseDir );
  }

  /**
  * Evaluate the access conditions and report if can continue
  * @return bool : true -> Access allowed
  */
  public function accessCheck() {
    return true;
  }




  /**
   * Form fields and validations
   *
   * @return object
   **/
  public function taxtermFormDefine( $request ) {

    $form = new FormController( 'taxtermForm', '/categories/sendcategoriesform' ); //actionform

    $form->setSuccess( 'redirect', '/' );
    $form->setField( 'id', array( 'type' => 'reserved', 'value' => null ) );
    $form->setField( 'idName', array( 'type' => 'reserved', 'value' => null ) );
    $form->setField( 'taxgroup', array( 'type' => 'reserved', 'value' => $request[1] ) );
    $form->setField( 'name', array( 'placeholder' => 'Name' ) );

    $form->setField( 'submit', array( 'type' => 'submit', 'value' => 'Save' ) );

    /***************************************************************************** VALIDATIONS */
    $form->setValidationRule( 'name', 'required' );


    if(isset($request[2])){
      $taxtermModel = new TaxonomytermModel();
      $dataVO = $taxtermModel->listItems( array('filters' => array('id' => $request[2] )))->fetch();
      if($dataVO){
        $form->loadVOValues( $dataVO );
      }
    }


    return $form;
  }


  /**
   * Returns necessary html form
   *
   * @param $form
   *
   * @return string
   **/
  public function taxtermFormGet( $form ) {
    $form->saveToSession();

    $this->template->assign("taxtermFormOpen", $form->getHtmpOpen());
    $this->template->assign("taxtermFormFields", $form->getHtmlFieldsArray());
    $this->template->assign("taxtermFormClose", $form->getHtmlClose());
    $this->template->assign("taxtermFormValidations", $form->getScriptCode());

    $this->template->setTpl('taxtermForm.tpl', 'geozzy');

    return $this->template->execToString();
  }


  /**
   * Action sendTaxtermForm
   *
   * @return void
   **/
  public function sendTaxtermForm() {
    $form = $this->actionTaxTermForm();
    $this->taxtermOk($form);

    if( $form->existErrors() ) {
      echo $form->jsonFormError();
    }
    else {
      echo $form->jsonFormOk();
    }
  }


  /**
   * Assigns the forms validations
   *
   * @return $form
   **/
  public function actionTaxTermForm() {
    $form = new FormController();
    if( $form->loadPostInput() ) {
      $form->validateForm();
    }
    else {
      $form->addFormError( 'El servidor no considera válidos los datos recibidos.', 'formError' );
    }

    if( !$form->existErrors() ){
      $valuesArray = $form->getValuesArray();
    }
    return $form;
  }


  /**
   * Edit/Create taxterm
   *
   * @return $taxterm
   **/
  public function taxtermOk( $form ) {

    //Si tod0 esta OK!
    if( !$form->processFileFields() ) {
      $form->addFormError( 'Ha sucedido un problema con los ficheros adjuntos. Puede que sea necesario subirlos otra vez.', 'formError' );
    }
    $res = false;
    if( !$form->existErrors() ){
      $valuesArray = $form->getValuesArray();
      // Donde diferenciamos si es un update o un create
      if( !isset($valuesArray['id']) || !$valuesArray['id'] ){
        $valuesArray['idName'] = str_replace(' ','',$valuesArray['name']);
        $valuesArray['idName'] .= '-'.$valuesArray['id'];
      }

      $taxterm = new TaxonomytermModel( $valuesArray );
      $taxterm->save();

      $res = $taxterm;
    }
    return $res;
  }


}
