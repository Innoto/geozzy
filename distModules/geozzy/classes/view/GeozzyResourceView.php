<?php
Cogumelo::load('coreView/View.php');



class GeozzyResourceView extends View
{


  public function __construct( $baseDir = false ){
    parent::__construct( $baseDir );

    common::autoIncludes();
    form::autoIncludes();
    filedata::autoIncludes();
    //user::autoIncludes();
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
    error_log( "GeozzyResourceView: getFormBlock()" );

    $langAvailable = false;
    $this->template->assign( 'JsLangAvailable', 'false' );
    $this->template->assign( 'JsLangDefault', 'false' );
    global $LANG_AVAILABLE;
    if( isset( $LANG_AVAILABLE ) && is_array( $LANG_AVAILABLE ) ) {
      $langAvailable = array_keys( $LANG_AVAILABLE );
      $langDefault = LANG_DEFAULT;
      $tmp = implode( "', '", $langAvailable );
      $this->template->assign( 'JsLangAvailable', "['".$tmp."']" );
      $this->template->assign( 'JsLangDefault', "'".LANG_DEFAULT."'" );
    }

    $form = new FormController( $formName, $urlAction );

    $form->setSuccess( 'accept', _( 'Thank you' ) );

    // 'image' 'type'=>'FOREIGN','vo' => 'FiledataModel','key' => 'id'
    // 'loc'   'type' => 'GEOMETRY'
    $campos = array(
      'title' => array(
        'translate' => true,
        'params' => array( 'label' => _( 'Title' ) ),
        'rules' => array( 'maxlength' => '100' )
      ),
      'shortDescription' => array(
        'translate' => true,
        'params' => array( 'label' => _( 'Short description' ) ),
        'rules' => array( 'maxlength' => '100' )
      ),
      'mediumDescription' => array(
        'translate' => true,
        'params' => array( 'label' => _( 'Medium description' ), 'type' => 'textarea', 'htmlEditor' => 'true' )
      ),
      'content' => array(
        'translate' => true,
        'params' => array( 'label' => _( 'Content' ), 'type' => 'textarea',
          'value' => '<p>ola mundo<br />...probando ;-)</p>', 'htmlEditor' => 'true' )
      ),
      /*
      'image' => array(
        'params' => array( 'label' => _( 'Image' ), 'type' => 'file', 'id' => 'imgResource',
          'placeholder' => 'Escolle unha imaxe', 'destDir' => '/imgResource' )
      ),
      */
      'defaultZoom' => array(
        'params' => array( 'label' => _( 'Map: Default zoom' ) ),
        'rules' => array( 'required' => true, 'max' => '20' )
      ),
      'headKeywords' => array(
        'params' => array( 'label' => _( 'SEO: Head Keywords' ) ),
        'rules' => array( 'maxlength' => '150' )
      ),
      'headDescription' => array(
        'translate' => true,
        'params' => array( 'label' => _( 'SEO: Head Description' ) ),
        'rules' => array( 'maxlength' => '150' )
      ),
      'headTitle' => array(
        'translate' => true,
        'params' => array( 'label' => _( 'SEO: Head Title' ) ),
        'rules' => array( 'maxlength' => '100' )
      )
    );


    foreach( $campos as $fieldName => $definition ) {
      if( !isset( $definition['params'] ) ) {
        $definition['params'] = false;
      }
      if( isset( $definition['translate'] ) && $definition['translate'] === true ) {
        foreach( $langAvailable as $lang ) {
          $form->setField( $fieldName.'_'.$lang, $definition['params'] );
          if( isset( $definition['rules'] ) ) {
            foreach( $definition['rules'] as $ruleName => $ruleParams ) {
              $form->setValidationRule( $fieldName.'_'.$lang, $ruleName, $ruleParams );
            }
          }
          $form->setFieldGroup( $fieldName.'_'.$lang, $fieldName.'_translate' );
        }
      }
      else {
        $form->setField( $fieldName, $definition['params'] );
        if( isset( $definition['rules'] ) ) {
          foreach( $definition['rules'] as $ruleName => $ruleParams ) {
            $form->setValidationRule( $fieldName, $ruleName, $ruleParams );
          }
        }
      }
    }


    $form->setValidationRule( 'title_'.$langDefault, 'required' );


    //Si es una edicion, añadimos el ID y cargamos los datos
    if( $valuesArray !== false ){
      $form->setField( 'id', array( 'type' => 'reserved', 'value' => null ) );
      $form->loadArrayValues( $valuesArray );
    }

    $form->setField( 'submit', array( 'type' => 'submit', 'value' => _( 'Send' ) ) );

    // Una vez que lo tenemos definido, guardamos el form en sesion
    $form->saveToSession();


    $this->template->assign( 'formOpen', $form->getHtmpOpen() );
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

    $form = new FormController();
    if( $form->loadPostInput() ) {
      $form->validateForm();
    }
    else {
      $form->addFormError( 'El servidor no considera válidos los datos recibidos.', 'formError' );
    }

    /*
    if( !$form->existErrors() ) {
      if( !$form->processFileFields() ) {
        $form->addFormError( 'Ha sucedido un problema con los ficheros adjuntos. Puede que sea '.
          'necesario subirlos otra vez.', 'formError' );
      }
    }
    */

    if( !$form->existErrors() ) {
      $valuesArray = $form->getValuesArray();

      if( $form->isFieldDefined( 'id' ) ) {
        $valuesArray['timeLastUpdate'] = date( "Y-m-d H:i:s", time() );
      }

      error_log( print_r( $valuesArray, true ) );

      $recurso = new ResourceModel( $valuesArray );
      $recurso->save();

      /*
      if($valuesArray['image']['values']){
        $recurso->setterDependence( 'image', new FiledataModel( $valuesArray['image']['values'] ) );
        $recurso->save( array( 'affectsDependences' => true ));
      }
      else {
        $recurso->save();
      }
      */
      echo $form->jsonFormOk();
    }
    else {
      $form->addFormError( 'NO SE HAN GUARDADO LOS DATOS.','formError' );
      echo $form->jsonFormError();
    }

  } // function actionResourceForm()

} // class ResourceView extends View
