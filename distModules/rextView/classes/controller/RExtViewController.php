<?php
geozzy::load( 'controller/RExtController.php' );

class RExtViewController extends RExtController implements RExtInterface {

  public function __construct( $defRTypeCtrl ){
    // error_log( 'RExtViewController::__construct' );
    // $this->numericFields = array( 'averagePrice' );
    parent::__construct( $defRTypeCtrl, new rextView(), 'rExtView_' );
  }


  public function getRExtData( $resId = false ) {
    // error_log( "RExtViewController: getRExtData( $resId )" );
    $rExtData = false;

    if( $resId === false ) {
      $resId = $this->defResCtrl->resObj->getter('id');
    }

    if( $this->taxonomies && is_array( $this->taxonomies ) && count( $this->taxonomies ) > 0 ) {
      $rExtData = array();

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

    // error_log( 'RExtViewController: getRExtData = '.print_r( $rExtData, true ) );
    return $rExtData;
  }


  /**
    Defino el formulario
   */
  public function manipulateForm( FormController $form ) {
    // error_log( "RExtViewController: manipulateForm()" );

    $rExtFieldNames = array();

    $fieldsInfo = array(
      'viewAlternativeMode' => array(
        'params' => array( 'label' => __( 'viewAlternativeMode' ),
          'type' => 'select',
          'class' => 'gzzSelect2',
          'options' => $this->defResCtrl->getOptionsTax( 'viewAlternativeMode' )
        )
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


  public function getFormBlockInfo( FormController $form ) {
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
    $templates['basic']->setTpl( 'rExtFormBasic.tpl', 'rextView' );
    $templates['basic']->assign( 'rExtName', $this->rExtName );
    $templates['basic']->assign( 'rExt', $formBlockInfo );

    $templates['full'] = new Template();
    $templates['full']->setTpl( 'rExtFormBlock.tpl', 'geozzy' );
    $templates['full']->assign( 'rExtName', $this->rExtName );
    $templates['full']->assign( 'rExt', $formBlockInfo );

    $formBlockInfo['template'] = $templates;

    return $formBlockInfo;
  }

  /**
   * Validaciones extra previas a usar los datos del recurso base
   */
  public function resFormRevalidate( FormController $form ) {
    // error_log( "RExtViewController: resFormRevalidate()" );

    // $this->evalFormUrlAlias( $form, 'urlAlias' );
  }

  /**
   * Creación-Edición-Borrado de los elementos del recurso base
   * Iniciar transaction
   */
  public function resFormProcess( FormController $form, ResourceModel $resource ) {
    // error_log( "RExtViewController: resFormProcess()" );

    if( !$form->existErrors() ) {
      foreach( $this->taxonomies as $tax ) {
        $taxFieldName = $this->addPrefix( $tax[ 'idName' ] );
        if( !$form->existErrors() && $form->isFieldDefined( $taxFieldName ) ) {
          $this->defResCtrl->setFormTax( $form, $taxFieldName, $tax[ 'idName' ], $form->getFieldValue( $taxFieldName ), $resource );
        }
      }
    }
  }


  public function resFormSuccess( FormController $form, ResourceModel $resource ) {
  }


  /**
   * Preparamos los datos para visualizar el Recurso
   */
  public function getViewBlockInfo( $resId = false ) {
    // error_log( "RExtViewController: getViewBlockInfo( $resId )" );

    $rExtViewBlockInfo = array(
      'template' => false,
      'data' => $this->getRExtData() // TODO: Esto ten que controlar os idiomas
    );

    return $rExtViewBlockInfo;
  }


  /**
   * Alteramos la visualizacion el Recurso
   *
   * @param Array $viewBlockInfo
   * @param String $templateName
   *
   * @return Array viewBlockInfo
   */
  public function alterViewBlockInfo( $viewBlockInfo, $templateName = false ) {
    // error_log( "RExtViewController: alterViewBlockInfo( viewBlockInfo, $templateName )" );
    /*
      $viewBlockInfo = array(
        'template' => array objTemplate,
        'data' => resourceData,
        'ext' => array rExt->viewBlockInfo
      );
    */

    if( isset( $viewBlockInfo['ext'][ $this->rExtName ]['data']['viewAlternativeMode'] ) ) {
      $term = array_pop( $viewBlockInfo['ext'][ $this->rExtName ]['data']['viewAlternativeMode'] );
      $viewAlternativeMode = $term[ 'idName' ];
      // error_log( 'RExtViewController->alterViewBlockInfo: viewAlternativeMode: ' . $viewAlternativeMode );

      if( isset($this->taxonomies['viewAlternativeMode']['initialTerms'][$viewAlternativeMode]) ) {
        $viewAlternativeModeConf = $this->taxonomies['viewAlternativeMode']['initialTerms'][$viewAlternativeMode];
      }

      if( strpos( $viewAlternativeMode, 'tpl' ) === 0 ) {
        if( isset($viewAlternativeModeConf['tpl']) ) {
          $altTplFile = $viewAlternativeModeConf['tpl'];
        }
        elseif( strpos( $viewAlternativeMode, 'tplApp' ) !== 0 ) {
          $altTplFile = $viewAlternativeMode.'.tpl';
        }
        else {
          $altTplFile = 'rExtViewAlt'.mb_substr( $viewAlternativeMode, 3 ).'.tpl';
        }

        if( isset($viewAlternativeModeConf['module']) ) {
          $altTplModule = ($viewAlternativeModeConf['module']) ? $viewAlternativeModeConf['module'] : 'cogumelo';
        }
        else {
          $altTplModule = $this->rExtName;
        }

        // error_log( 'RExtViewController->alterViewBlockInfo: $altTplFile: '.$altTplFile.' in module '.$altTplModule );

        if( $templateName ) {
          // error_log( 'RExtViewController->alterViewBlockInfo: cambio el .tpl de '.$templateName );
          $viewBlockInfo['template'][ $templateName ]->setTpl( $altTplFile, $altTplModule );
        }
        else {
          foreach( $viewBlockInfo['template'] as $templateName => $templateObj ) {
            // error_log( 'RExtViewController->alterViewBlockInfo: cambio el .tpl de '.$templateName );
            $templateObj->setTpl( $altTplFile, $altTplModule );
          }
        }
      }
      elseif( strpos( $viewAlternativeMode, 'view' ) === 0 ) {
        if( isset($viewAlternativeModeConf['view']) ) {
          $altViewClass = $viewAlternativeModeConf['view'];
        }
        else {
          $altViewClass = 'RExtViewAlt'.mb_substr( $viewAlternativeMode, 4 );
        }
        $altViewClassFile = $altViewClass.'.php';

        if( isset($viewAlternativeModeConf['module']) ) {
          $altViewClassModule = ($viewAlternativeModeConf['module']) ? $viewAlternativeModeConf['module'] : 'cogumelo';
        }
        else {
          $altViewClassModule = $this->rExtName;
        }

        // error_log( 'RExtViewController->alterViewBlockInfo: ClassFile: '.$altViewClassFile.' in module '.$altViewClassModule );

        // rextView::load( 'view/'.$altViewClassFile );
        eval( $altViewClassModule.'::load( "view/".$altViewClassFile );' );
        $altViewCtrl = new $altViewClass( $this );
        $viewBlockInfo = $altViewCtrl->alterViewBlockInfo( $viewBlockInfo, $templateName );
      }
    }

    return $viewBlockInfo;
  }


} // class RExtViewController
