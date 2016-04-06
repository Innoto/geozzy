<?php
Cogumelo::load('coreView/View.php');



class UrlAliasView extends View
{


  public function __construct( $baseDir = false ){
    parent::__construct( $baseDir );

    common::autoIncludes();
    form::autoIncludes();
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
    error_log( "UrlAliasView: getFormBlock()" );

    $langAvailable = false;
    $langOptions = array( '' => _( 'Any' ) );
    global $LANG_AVAILABLE;
    if( isset( $LANG_AVAILABLE ) && is_array( $LANG_AVAILABLE ) ) {
      $langAvailable = array_keys( $LANG_AVAILABLE );
      $langDefault = LANG_DEFAULT;

      foreach( $LANG_AVAILABLE as $langId => $langValues ) {
        $langOptions[ $langId ] = $langValues[ 'name' ];
      }
    }


    $form = new FormController( $formName, $urlAction );

    $form->setSuccess( 'accept', _( 'Thank you' ) );
    //$form->setSuccess( 'redirect', SITE_URL . 'admin#resource/list' );
    $form->setSuccess( 'reload' );

    $form->setField( 'http', array( 'label' => _( 'Action' ), 'type' => 'select',
      'options' => array( '301' => _( 'Redirect 301' ), '0' => _( 'Alias' ) )
    ) );

    $form->setField( 'canonical', array( 'type' => 'checkbox',
      'options'=> array( 'canonical' => _( 'Is canonical URL' ) )
    ) );

    $form->setField( 'lang', array( 'label' => _( 'Select lang' ), 'type' => 'select',
      'options'=> $langOptions
    ) );

    $form->setField( 'urlFrom', array( 'label' => _( 'URL from (internal)' ), 'size' => '50',
      'placeholder' => '/internal/content' ) );
    $form->setValidationRule( 'urlFrom', 'maxlength', '2000' );
    $form->setValidationRule( 'urlFrom', 'required' );

    $form->setField( 'urlTo', array( 'label' => _( 'URL to (internal or external)' ), 'size' => '50',
      'placeholder' => '/internal/content or http://external.com/content' ) );
    $form->setValidationRule( 'urlTo', 'maxlength', '2000' );

    $form->setField( 'resource', array( 'label' => _( 'Resource' ), 'placeholder' => 'ID', 'size' => '5' ) );
    $form->setValidationRule( 'resource', 'digits' );
    //$form->setValidationRule( $fieldName, $ruleName, $ruleParams );


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
  public function actionForm() {
    error_log( "UrlAliasView: actionForm()" );

    $form = new FormController();
    if( $form->loadPostInput() ) {
      $form->validateForm();
    }
    else {
      $form->addFormError( 'El servidor no considera válidos los datos recibidos.', 'formError' );
    }

    if( !$form->existErrors() ) {
      $valuesArray = $form->getValuesArray();

      $valuesArray[ 'canonical' ] = ( $valuesArray[ 'canonical' ] === 'canonical' ) ? 1 : 0;

      if( $valuesArray[ 'resource' ] !== '' ) {
        $valuesArray[ 'urlTo' ] = null;
      }
      else {
        $valuesArray[ 'resource' ] = null;
      }

      error_log( print_r( $valuesArray, true ) );

      $elemModel = new UrlAliasModel( $valuesArray );
      $elemModel->save();

      echo $form->getJsonOk();
    }
    else {
      $form->addFormError( 'NO SE HAN GUARDADO LOS DATOS.','formError' );
      echo $form->getJsonError();
    }

  } // function actionForm()


} // class ResourceView extends View
