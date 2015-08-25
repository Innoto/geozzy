<?php


class RExtViewController extends RExtController implements RExtInterface {

  public $numericFields = false;


  public function __construct( $defRTypeCtrl ){
    error_log( 'RExtViewController::__construct' );

    // $this->numericFields = array( 'averagePrice' );

    parent::__construct( $defRTypeCtrl, new rextView(), 'rExtView_' );
  }


  public function getRExtData( $resId ) {
    error_log( "RExtViewController: getRExtData( $resId )" );
    $rExtData = false;

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
        'params' => array( 'label' => __( 'viewAlternativeMode' ), 'type' => 'select',
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

    $form->setField( 'rExtViewFieldNames', array( 'type' => 'reserved', 'value' => $rExtFieldNames ) );

    $form->saveToSession();

    return( $rExtFieldNames );
  } // function manipulateForm()



  /**
    Validaciones extra previas a usar los datos del recurso base
   */
  public function resFormRevalidate( FormController $form ) {
    // error_log( "RExtViewController: resFormRevalidate()" );

    // $this->evalFormUrlAlias( $form, 'urlAlias' );
  }

  /**
    Creación-Edición-Borrado de los elementos del recurso base
    Iniciar transaction
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

  /**
    Enviamos el OK-ERROR a la BBDD y al formulario
    Finalizar transaction
   */
  public function resFormSuccess( FormController $form, ResourceModel $resource ) {
    // error_log( "RExtViewController: resFormSuccess()" );

  }



  /**
    Visualizamos el Recurso
   */
  public function getViewBlock( Template $resBlock ) {
    // error_log( "RExtViewController: getViewBlock()" );
    $template = false;

    $resId = $this->defResCtrl->resObj->getter('id');
    $rExtData = $this->getRExtData( $resId );

    if( isset( $rExtData[ 'viewAlternativeMode' ] ) ) {
      $term = array_pop( $rExtData[ 'viewAlternativeMode' ] );
      $viewAlternativeMode = $term[ 'idName' ];
      error_log( 'viewAlternativeMode: ' . $viewAlternativeMode );

      if( strpos( $viewAlternativeMode, 'tpl' ) === 0 ) {
        if( strpos( $viewAlternativeMode, 'tplApp' ) !== 0 ) {
          $tplFile = $viewAlternativeMode.'.tpl';
        }
        else {
          $tplFile = 'rExtViewAlt'.substr( $viewAlternativeMode, 3 ).'.tpl';
        }
        $module = 'rextView';
        error_log( '$tplFile: '.$tplFile );
        $existFile = ModuleController::getRealFilePath( 'classes/view/templates/'.$tplFile, $module );
        if( $existFile ) {
          $resBlock->setTpl( $tplFile, $module );
        }
      }
      elseif( strpos( $viewAlternativeMode, 'view' ) === 0 ) {
        $altViewClass = 'RExtViewAlt'.substr( $viewAlternativeMode, 4 );
        $altViewClassFile = $altViewClass.'.php';
        $module = 'rextView';
        error_log( '$altViewClassFile: '.$altViewClassFile );
        $existFile = ModuleController::getRealFilePath( 'classes/view/'.$altViewClassFile, $module );
        if( $existFile ) {
          rextView::load( 'view/'.$altViewClassFile );
          $altViewCtrl = new $altViewClass( $this->defRTypeCtrl );
          $template = $altViewCtrl->getViewBlock( $resBlock );
        }
      }
    }

    return $template;
  }

} // class RExtViewController

