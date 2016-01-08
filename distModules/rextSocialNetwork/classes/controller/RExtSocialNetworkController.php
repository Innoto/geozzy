<?php


class RExtSocialNetworkController extends RExtController implements RExtInterface {

  public $numericFields = false;


  public function __construct( $defRTypeCtrl ){
    error_log( 'RExtSocialNetworkController::__construct' );

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
    $rExtObj = $rExtList->fetch();

    if( $rExtObj ) {
      $rExtData = $rExtObj->getAllData( 'onlydata' );

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
        'params' => array( 'type' => 'checkbox', 'class' => 'switchery', 'options'=> array( '1' => __('Activar facebook') ))
      ),
      'textFb' => array(
        'params' => array( 'label' => __( 'Text to share on facebook' ), 'type' => 'textarea' ),
        'rules' => array( 'maxlength' => '2000' )
      ),
      'activeTwitter' => array(
        'params' => array( 'type' => 'checkbox', 'class' => 'switchery', 'options'=> array( '1' => 'Activar twitter' ))
      ),
      'textTwitter' => array(
        'params' => array( 'label' => __( 'Text to share on twitter' ), 'type' => 'textarea' ),
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

    $formBlockInfo = array(
      'template' => false,
      'data' => false,
      'dataForm' => false
    );

    $prefixedFieldNames = $this->prefixArray( $form->getFieldValue( $this->addPrefix( 'FieldNames' ) ) );
    error_log( 'prefixedFieldNames =' . print_r( $prefixedFieldNames, true ) );

    $formBlockInfo['dataForm'] = array(
      'formFieldsArray' => $form->getHtmlFieldsArray( $prefixedFieldNames ),
      'formFields' => $form->getHtmlFieldsAndGroups(),
    );

    if( $form->getFieldValue( 'id' ) ) {
      $formBlockInfo['data'] = $this->getRExtData();
    }

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
    error_log( "RExtSocialNetworkController: resFormProcess()" );

    if( !$form->existErrors() ) {
      $valuesArray = $this->getRExtFormValues( $form->getValuesArray(), $this->numericFields );

      $valuesArray[ 'resource' ] = $resource->getter( 'id' );

      // error_log( 'NEW RExtSocialNetworkModel: ' . print_r( $valuesArray, true ) );
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
    Visualizamos el Recurso
   */
  public function getViewBlock( Template $resBlock ) {
    // error_log( "RExtSocialNetworkController: getViewBlock()" );
    $template = false;

    $resId = $this->defResCtrl->resObj->getter('id');
    $rExtData = $this->getRExtData( $resId );

    if( $rExtData ) {
      $template = new Template();

      $externalSocialNetwork = $this->defResCtrl->resObj->getter( 'externalSocialNetwork' );
      $template->assign( 'externalSocialNetwork', $externalSocialNetwork );

      $rExtDataPrefixed = $this->prefixArrayKeys( $rExtData );
      foreach( $rExtDataPrefixed as $key => $value ) {
        $template->assign( $key, $rExtDataPrefixed[ $key ] );
        error_log( $key . ' === ' . print_r( $rExtDataPrefixed[ $key ], true ) );
      }

      // Vacio campos numericos NULL
      if( $this->numericFields ) {
        foreach( $this->numericFields as $fieldName ) {
          $fieldName = $this->addPrefix( $fieldName );
          if( !isset( $rExtDataPrefixed[ $fieldName ] ) || !$rExtDataPrefixed[ $fieldName ] ) {
            $template->assign( $fieldName, '##NULL-VACIO##' );
          }
        }
      }

      // Procesamos as taxonomías asociadas para mostralas en CSV
      foreach( $this->taxonomies as $tax ) {
        $taxFieldName = $this->addPrefix( $tax[ 'idName' ] );
        $taxFieldValue = '';

        if( isset( $rExtDataPrefixed[ $taxFieldName ] ) ) {
          $terms = array();
          foreach( $rExtDataPrefixed[ $taxFieldName ] as $termInfo ) {
            $terms[] = $termInfo['name_es'].' ('.$termInfo['id'].')';
          }
          $taxFieldValue = implode( ', ', $terms );
        }
        $template->assign( $taxFieldName, $taxFieldValue );
      }

      $template->assign( 'rExtFieldNames', array_keys( $rExtDataPrefixed ) );
      $template->setTpl( 'rExtViewBlock.tpl', 'rextSocialNetwork' );

    }

    return $template;
  }



  /**
    Preparamos los datos para visualizar el Recurso
   */
  public function getViewBlockInfo() {
    error_log( "RExtSocialNetworkController: getViewBlockInfo()" );

    $rExtViewBlockInfo = array(
      'template' => false,
      'data' => $this->getRExtData() // TODO: Esto ten que controlar os idiomas
    );

    if( $rExtViewBlockInfo['data'] ) {
      $template = new Template();

      $rExtViewBlockInfo['data']['externalSocialNetwork'] = $resId = $this->defResCtrl->resObj->getter('externalSocialNetwork');

      $template->assign( 'rExt', array( 'data' => $rExtViewBlockInfo['data'] ) );

      $template->setTpl( 'rExtViewBlock.tpl', 'rextSocialNetwork' );
      if( isset( $rExtViewBlockInfo['data'][ 'socialNetworkContentType' ] ) ) {
        $socialNetworkContentType = array_pop( $rExtViewBlockInfo['data'][ 'socialNetworkContentType' ] );
        $socialNetworkContentType = $socialNetworkContentType[ 'idName' ];
        error_log( 'socialNetworkContentType: ' . $socialNetworkContentType );
        switch( $socialNetworkContentType ) {
          case 'page':
            $template->setTpl( 'rExtViewBlockPage.tpl', 'rextSocialNetwork' );
            break;
          case 'file':
            $template->setTpl( 'rExtViewBlockPage.tpl', 'rextSocialNetwork' );
            break;
          case 'media':
            $template->setTpl( 'rExtViewBlockPage.tpl', 'rextSocialNetwork' );
            break;
          case 'image':
            $template->setTpl( 'rExtViewBlockImage.tpl', 'rextSocialNetwork' );
            break;
          case 'audio':
            $template->setTpl( 'rExtViewBlockPage.tpl', 'rextSocialNetwork' );
            break;
          case 'video':
            $template->setTpl( 'rExtViewBlockVideo.tpl', 'rextSocialNetwork' );
            break;
        }
      }

      $rExtViewBlockInfo['template'] = array( 'full' => $template );
    }

    return $rExtViewBlockInfo;
  }

} // class RExtSocialNetworkController
