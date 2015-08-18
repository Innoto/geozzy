<?php


class RExtUrlController extends RExtController implements RExtInterface {


  public function __construct( $defRTypeCtrl ){
    error_log( 'RExtUrlController::__construct' );

    parent::__construct( $defRTypeCtrl, new rextUrl(), 'rExtUrl_' );
  }


  public function getRExtData( $resId ) {
    error_log( "RExtUrlController: getRExtData( $resId )" );
    $rExtData = false;

    // Cargo los datos de TAX con los que está asociado el recurso
    $taxTerms = $this->defResCtrl->getResTerms( $resId );
    if( $taxTerms ) {
      $rExtData = array();
      foreach( $this->taxonomies as $tax ) {
        // TODO: Separar los terms por taxonomia
        $rExtData[ $tax['idName'] ] = $taxTerms;
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
      'URL' => array(
        'params' => array( 'label' => __( 'External URL' ) ),
        'rules' => array( 'maxlength' => '2000' )
      ),
      'urlContentType' => array(
        'params' => array( 'label' => __( 'URL content type' ), 'type' => 'select',
          'options' => $this->defResCtrl->getOptionsTax( 'urlContentType' )
        )
      ),
      'embed' => array(
        'params' => array( 'label' => __( 'Embed HTML' ) ),
        'rules' => array( 'maxlength' => '2000' )
      ),
      'author' => array(
        'params' => array( 'label' => __( 'Author' ) ),
        'rules' => array( 'maxlength' => '500' )
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

    $form->setField( 'rExtUrlFieldNames', array( 'type' => 'reserved', 'value' => $rExtFieldNames ) );

    $form->saveToSession();

    return( $rExtFieldNames );
  } // function manipulateForm()



  /**
    Validaciones extra previas a usar los datos del recurso base
   */
  public function resFormRevalidate( FormController $form ) {
    // error_log( "RExtUrlController: resFormRevalidate()" );

    // $this->evalFormUrlAlias( $form, 'urlAlias' );
  }

  /**
    Creación-Edición-Borrado de los elementos del recurso base
    Iniciar transaction
   */
  public function resFormProcess( FormController $form, ResourceModel $resource ) {
    // error_log( "RExtUrlController: resFormProcess()" );

    foreach( $this->taxonomies as $tax ) {
      $taxFieldName = $this->addPrefix( $tax[ 'idName' ] );
      if( !$form->existErrors() && $form->isFieldDefined( $taxFieldName ) ) {
        $this->defResCtrl->setFormTax( $form, $taxFieldName, $tax[ 'idName' ], $form->getFieldValue( $taxFieldName ), $resource );
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
  public function getViewBlock( ResourceModel $resource, Template $resBlock ) {
    // error_log( "RExtUrlController: getViewBlock()" );
    $template = false;

    $rExtData = $this->getRExtData( $resource->getter('id') );
    if( $rExtData ) {
      $template = new Template();

      $rExtData = $this->prefixArrayKeys( $rExtData );
      foreach( $rExtData as $key => $value ) {
        $template->assign( $key, $rExtData[ $key ] );
        error_log( $key . ' === ' . print_r( $rExtData[ $key ], true ) );
      }

      $template->assign( 'rExtFieldNames', array_keys( $rExtData ) );
      $template->setTpl( 'rExtViewBlock.tpl', 'rextUrl' );
    }

    return $template;
  }

} // class RExtUrlController
