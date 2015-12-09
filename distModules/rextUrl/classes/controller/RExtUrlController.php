<?php


class RExtUrlController extends RExtController implements RExtInterface {

  public $numericFields = false;


  public function __construct( $defRTypeCtrl ){
    error_log( 'RExtUrlController::__construct' );

    // $this->numericFields = array( 'averagePrice' );

    parent::__construct( $defRTypeCtrl, new rextUrl(), 'rExtUrl_' );
  }


  public function getRExtData( $resId = false ) {
    // error_log( "RExtUrlController: getRExtData( $resId )" );
    $rExtData = false;

    if( $resId === false ) {
      $resId = $this->defResCtrl->resObj->getter('id');
    }

    $rExtModel = new RExtUrlModel();
    $rExtList = $rExtModel->listItems( array( 'filters' => array( 'resource' => $resId ) ) );
    $rExtObj = $rExtList->fetch();

    if( $rExtObj ) {
      $rExtData = $rExtObj->getAllData( 'onlydata' );

      // Cargo todos los TAX terms del recurso agrupados por idName de Taxgroup
      $termsGroupedIdName = $this->defResCtrl->getTermsInfoByGroupIdName( $resId );
      if( $termsGroupedIdName !== false ) {
        foreach( $this->taxonomies as $tax ) {
          if( isset( $termsGroupedIdName[ $tax[ 'idName' ] ] ) ) {
            $rExtData[ $tax['idName'] ] = $termsGroupedIdName[ $tax[ 'idName' ] ];
          }
        }
      }
    }

    // error_log( 'RExtUrlController: getRExtData = '.print_r( $rExtData, true ) );
    return $rExtData;
  }


  /**
    Defino el formulario
   */
  public function manipulateForm( FormController $form ) {
    // error_log( "RExtUrlController: manipulateForm()" );

    $rExtFieldNames = array();

    $fieldsInfo = array(
      'author' => array(
        'params' => array( 'label' => __( 'Author' ) ),
        'rules' => array( 'maxlength' => '500' )
      ),
      'urlContentType' => array(
        'params' => array( 'label' => __( 'URL content type' ), 'type' => 'select',
          'options' => $this->defResCtrl->getOptionsTax( 'urlContentType' )
        )
      ),
      'embed' => array(
        'params' => array( 'label' => __( 'Embed HTML' ), 'type' => 'textarea' ),
        'rules' => array( 'maxlength' => '2000' )
      )

    );

    $form->definitionsToForm( $this->prefixArrayKeys( $fieldsInfo ) );

    // Valadaciones extra
    // $form->setValidationRule( 'hotelName_'.$form->langDefault, 'required' );

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
    error_log( "RExtUrlController: resFormRevalidate()" );
    $valueEmbed = $form->getFieldValue( $this->addPrefix( 'embed' ) );
    $valueExternalUrl = $form->getFieldValue( 'externalUrl' );
    
    if( !$valueExternalUrl && !$valueEmbed ) {
      //$form->addFormError( $msgText, $msgClass = false )
      $form->addFieldRuleError( $this->addPrefix( 'embed' ), 'required' );
      $form->addFieldRuleError( 'externalUrl', 'required' );
    }
    // $this->evalFormUrlAlias( $form, 'urlAlias' );
  }

  /**
    Creación-Edición-Borrado de los elementos del recurso base
    Iniciar transaction
   */
  public function resFormProcess( FormController $form, ResourceModel $resource ) {
    error_log( "RExtUrlController: resFormProcess()" );

    if( !$form->existErrors() ) {
      $valuesArray = $this->getRExtFormValues( $form->getValuesArray(), $this->numericFields );

      $valuesArray[ 'resource' ] = $resource->getter( 'id' );

      // error_log( 'NEW RExtUrlModel: ' . print_r( $valuesArray, true ) );
      $rExtModel = new RExtUrlModel( $valuesArray );
      if( $rExtModel === false ) {
        $form->addFormError( 'No se ha podido guardar el recurso. (rExtModel)','formError' );
      }
    }

    if( !$form->existErrors() ) {
      foreach( $this->taxonomies as $tax ) {
        $taxFieldName = $this->addPrefix( $tax[ 'idName' ] );
        if( !$form->existErrors() && $form->isFieldDefined( $taxFieldName ) ) {
          $this->defResCtrl->setFormTax( $form, $taxFieldName, $tax[ 'idName' ], $form->getFieldValue( $taxFieldName ), $resource );
        }
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
    // error_log( "RExtUrlController: resFormSuccess()" );

  }



  /**
    Visualizamos el Recurso
   */
  public function getViewBlock( Template $resBlock ) {
    // error_log( "RExtUrlController: getViewBlock()" );
    $template = false;

    $resId = $this->defResCtrl->resObj->getter('id');
    $rExtData = $this->getRExtData( $resId );

    if( $rExtData ) {
      $template = new Template();

      $externalUrl = $this->defResCtrl->resObj->getter( 'externalUrl' );
      $template->assign( 'externalUrl', $externalUrl );

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
      $template->setTpl( 'rExtViewBlock.tpl', 'rextUrl' );

      if( isset( $rExtData[ 'urlContentType' ] ) ) {
        $urlContentType = array_pop( $rExtData[ 'urlContentType' ] );
        $urlContentType = $urlContentType[ 'idName' ];
        error_log( 'urlContentType: ' . $urlContentType );
        switch( $urlContentType ) {
          case 'page':
            $template->setTpl( 'rExtViewBlockPage.tpl', 'rextUrl' );
            break;
          case 'file':
            $template->setTpl( 'rExtViewBlockPage.tpl', 'rextUrl' );
            break;
          case 'media':
            $template->setTpl( 'rExtViewBlockPage.tpl', 'rextUrl' );
            break;
          case 'image':
            $template->setTpl( 'rExtViewBlockImage.tpl', 'rextUrl' );
            break;
          case 'audio':
            $template->setTpl( 'rExtViewBlockPage.tpl', 'rextUrl' );
            break;
          case 'video':
            $template->setTpl( 'rExtViewBlockVideo.tpl', 'rextUrl' );
            break;
        }
      }

    }

    return $template;
  }



  /**
    Preparamos los datos para visualizar el Recurso
   */
  public function getViewBlockInfo() {
    error_log( "RExtUrlController: getViewBlockInfo()" );

    $rExtViewBlockInfo = array(
      'template' => false,
      'data' => $this->getRExtData() // TODO: Esto ten que controlar os idiomas
    );

    if( $rExtViewBlockInfo['data'] ) {
      $template = new Template();

      $rExtViewBlockInfo['data']['externalUrl'] = $resId = $this->defResCtrl->resObj->getter('externalUrl');

      $template->assign( 'rExt', array( 'data' => $rExtViewBlockInfo['data'] ) );

      $template->setTpl( 'rExtViewBlock.tpl', 'rextUrl' );
      if( isset( $rExtViewBlockInfo['data'][ 'urlContentType' ] ) ) {
        $urlContentType = array_pop( $rExtViewBlockInfo['data'][ 'urlContentType' ] );
        $urlContentType = $urlContentType[ 'idName' ];
        error_log( 'urlContentType: ' . $urlContentType );
        switch( $urlContentType ) {
          case 'page':
            $template->setTpl( 'rExtViewBlockPage.tpl', 'rextUrl' );
            break;
          case 'file':
            $template->setTpl( 'rExtViewBlockPage.tpl', 'rextUrl' );
            break;
          case 'media':
            $template->setTpl( 'rExtViewBlockPage.tpl', 'rextUrl' );
            break;
          case 'image':
            $template->setTpl( 'rExtViewBlockImage.tpl', 'rextUrl' );
            break;
          case 'audio':
            $template->setTpl( 'rExtViewBlockPage.tpl', 'rextUrl' );
            break;
          case 'video':
            $template->setTpl( 'rExtViewBlockVideo.tpl', 'rextUrl' );
            break;
        }
      }

      $rExtViewBlockInfo['template'] = array( 'full' => $template );
    }

    return $rExtViewBlockInfo;
  }

} // class RExtUrlController
