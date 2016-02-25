<?php


class RExtSocialNetworkController extends RExtController implements RExtInterface {

  public $numericFields = false;


  public function __construct( $defRTypeCtrl ){
    // error_log( 'RExtSocialNetworkController::__construct' );

    // $this->numericFields = array( 'averagePrice' );

    parent::__construct( $defRTypeCtrl, new rextSocialNetwork(), 'rExtSocialNetwork_' );
  }


  public function getRExtData( $resId = false ) {
    // error_log( "RExtSocialNetworkController: getRExtData( $resId )" );
    $rExtData = false;

    if( $resId === false ) {
      $resId = $this->defResCtrl->resObj->getter('id');
    }

    $rExtModel = new RExtSocialNetworkModel();
    $rExtList = $rExtModel->listItems( array( 'filters' => array( 'resource' => $resId ) ) );
    if( $rExtList ) {
      $rExtObj = $rExtList->fetch();
    }

    if( $rExtObj ) {
      $rExtData = $rExtObj->getAllData( 'onlydata' );
      $rExtDataFields = $rExtObj->getCols();
      foreach( $rExtDataFields as $key => $value ) {
        error_log( "=== Res Col: $key" );
        if( !isset( $rExtData[ $key ] ) ) {
          $rExtData[ $key ] = $rExtObj->getter( $key );
        }
      }
    }

    // error_log( 'RExtSocialNetworkController: getRExtData = '.print_r( $rExtData, true ) );
    return $rExtData;
  }


  /**
    Defino el formulario
   */
  public function manipulateForm( FormController $form ) {
    // error_log( "RExtSocialNetworkController: manipulateForm()" );

    $rExtFieldNames = array();

    $fieldsInfo = array(
      'activeFb' => array(
        'params' => array( 'type' => 'checkbox', 'class' => 'switchery', 'options'=> array( '1' => __('Activate share on facebook') ))
      ),
      'textFb' => array(
        'translate' => true,
        'params' => array( 'label' => __( 'Text to share on facebook' ), 'type' => 'textarea', 'placeholder' => 'You should visit #TITLE#. Seen in en #URL#' ),
        'rules' => array( 'maxlength' => '2000' )
      ),
      'activeTwitter' => array(
        'params' => array( 'type' => 'checkbox', 'class' => 'switchery', 'options'=> array( '1' => 'Activate share on twitter' ))
      ),
      'textTwitter' => array(
        'translate' => true,
        'params' => array( 'label' => __( 'Text to share on twitter' ), 'type' => 'textarea', 'placeholder' => 'I liked this place: #TITLE# via #URL#' ),
        'rules' => array( 'maxlength' => '2000' )
      )

    );

    $form->definitionsToForm( $this->prefixArrayKeys( $fieldsInfo ) );

    // Si es una edicion, añadimos el ID y cargamos los datos
    $valuesArray = $this->getRExtData( $form->getFieldValue( 'id' ) );
    if( $valuesArray ) {
      $valuesArray = $this->prefixArrayKeys( $valuesArray );
      $form->setField( $this->addPrefix( 'id' ), array( 'type' => 'reserved', 'value' => null ) );

      // Limpiando la informacion de terms para el form
      if( $this->taxonomies ) {
        foreach( $this->taxonomies as $tax ) {
          $taxFieldName = $this->addPrefix( $tax[ 'idName' ] );
          if( isset( $valuesArray[ $taxFieldName ] ) && is_array( $valuesArray[ $taxFieldName ] ) ) {
            $taxFieldValues = array();
            foreach( $valuesArray[ $taxFieldName ] as $value ) {
              $taxFieldValues[] = ( is_array( $value ) ) ? $value[ 'id' ] : $value;
            }
            $valuesArray[ $taxFieldName ] = $taxFieldValues;
          }
        }
      }

      $form->loadArrayValues( $valuesArray );
    }

    // Add RExt info to form
    foreach( $fieldsInfo as $fieldName => $info ) {
      if( isset( $info[ 'translate' ] ) && $info[ 'translate' ] ) {
        $rExtFieldNames = array_merge( $rExtFieldNames, $form->multilangFieldNames( $fieldName ) );
      }
      else {
        $rExtFieldNames[] = $fieldName;
      }
    }

    $rExtFieldNames[] = 'FieldNames';
    $form->setField( $this->addPrefix( 'FieldNames' ), array( 'type' => 'reserved', 'value' => $rExtFieldNames ) );

    $form->saveToSession();

    return( $rExtFieldNames );
  } // function manipulateForm()

  /**
    getFormBlockInfo
  */
  public function getFormBlockInfo( FormController $form ) {

    // error_log( "RExtContactController: getFormBlockInfo()" );

    $formBlockInfo = array(
      'template' => false,
      'data' => false,
      'dataForm' => false
    );

    $prefixedFieldNames = $this->prefixArray( $form->getFieldValue( $this->addPrefix( 'FieldNames' ) ) );
    // error_log( 'prefixedFieldNames =' . print_r( $prefixedFieldNames, true ) );

    $formBlockInfo['dataForm'] = array(
      'formFieldsArray' => $form->getHtmlFieldsArray( $prefixedFieldNames ),
      'formFields' => $form->getHtmlFieldsAndGroups(),
    );

    if( $form->getFieldValue( 'id' ) ) {
      $formBlockInfo['data'] = $this->getRExtData();
    }

    $templates['basic'] = new Template();
    $templates['basic']->setTpl( 'rExtFormBasic.tpl', 'rextSocialNetwork' );
    $templates['basic']->assign( 'rExt', $formBlockInfo );
    $templates['basic']->assign('textFb', $form->multilangFieldNames( 'rExtSocialNetwork_textFb' ));
    $templates['basic']->assign('textTwitter', $form->multilangFieldNames( 'rExtSocialNetwork_textTwitter' ));


    $templates['full'] = new Template();
    $templates['full']->setTpl( 'rExtFormBlock.tpl', 'geozzy' );
    $templates['full']->assign( 'rExtName', $this->rExtName );
    $templates['full']->assign( 'rExt', $formBlockInfo );

    $formBlockInfo['template'] = $templates;

    return $formBlockInfo;
  }

  /**
    Validaciones extra previas a usar los datos del recurso base
   */
  public function resFormRevalidate( FormController $form ) {

    // $this->evalFormSocialNetworkAlias( $form, 'socialNetworkAlias' );
  }

  /**
    Creación-Edición-Borrado de los elementos del recurso base
    Iniciar transaction
   */
  public function resFormProcess( FormController $form, ResourceModel $resource ) {
    // error_log( "RExtSocialNetworkController: resFormProcess()" );



    if( !$form->existErrors() ) {
      $valuesArray = $this->getRExtFormValues( $form->getValuesArray(), $this->numericFields );

      $valuesArray[ 'resource' ] = $resource->getter( 'id' );

      // error_log( 'NEW RExtSocialNetworkModel: ' . print_r( $valuesArray, true ) );

      $textFb = $form->multilangFieldNames( 'textFb' );
      foreach( $textFb as $text ) {
        if ($valuesArray[$text]==""){
          $valuesArray[$text] = $form->getFieldParam('rExtSocialNetwork_'.$text, 'placeholder');
          $form->setFieldValue('rExtSocialNetwork_'.$text, $form->getFieldParam('rExtSocialNetwork_'.$text, 'placeholder'));
        }
      }
      $twitterFb = $form->multilangFieldNames( 'textTwitter' );
      foreach( $twitterFb as $text ) {
        if( $valuesArray[$text]=="" ) {
          $valuesArray[$text] = $form->getFieldParam('rExtSocialNetwork_'.$text, 'placeholder');
          $form->setFieldValue('rExtSocialNetwork_'.$text, $form->getFieldParam('rExtSocialNetwork_'.$text, 'placeholder'));
        }
      }

      $rExtModel = new RExtSocialNetworkModel( $valuesArray );

      if( $rExtModel === false ) {
        $form->addFormError( 'No se ha podido guardar el recurso. (rExtModel)','formError' );
      }
    }


    if( !$form->existErrors() ) {
      $saveResult = $rExtModel->save();
      if( $saveResult === false ) {
        $form->addFormError( 'No se ha podido guardar el recurso. (rExtModel)','formError' );
      }
    }
  }

  /**
    Enviamos el OK-ERROR a la BBDD y al formulario
    Finalizar transaction
   */
  public function resFormSuccess( FormController $form, ResourceModel $resource ) {
    // error_log( "RExtSocialNetworkController: resFormSuccess()" );

  }


  /**
    Preparamos los datos para visualizar el Recurso
   */
  public function getViewBlockInfo() {
    // error_log( "RExtSocialNetworkController: getViewBlockInfo()" );

    $rExtViewBlockInfo = array(
      'template' => false,
      'data' => $this->getRExtData() // TODO: Esto ten que controlar os idiomas
    );

    if( $rExtViewBlockInfo['data'] ) {
      $template = new Template();

      foreach( $rExtViewBlockInfo['data'] as $key => $socialField ) {
        $text[$key] = str_replace('#TITLE#', $this->defResCtrl->resObj->getter('title'), $socialField );
        $text2[$key] = str_replace('#URL#', SITE_HOST.$this->defResCtrl->resData['urlAlias'], $text[$key] );
        $rExtViewBlockInfo['data'][$key] = $text2[$key];
      }

      $template->assign( 'rExt', array( 'data' => $rExtViewBlockInfo['data'] ) );
      $template->setTpl( 'rExtViewBlock.tpl', 'rextSocialNetwork' );

      $rExtViewBlockInfo['template'] = array( 'full' => $template );
    }

    return $rExtViewBlockInfo;
  }

} // class RExtSocialNetworkController
