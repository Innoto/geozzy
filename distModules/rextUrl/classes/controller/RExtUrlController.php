<?php
geozzy::load( 'controller/RExtController.php' );

class RExtUrlController extends RExtController implements RExtInterface {

  public $numericFields = false;


  public function __construct( $defRTypeCtrl ){
    // error_log( 'RExtUrlController::__construct' );

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
    $rExtList = $rExtModel->listItems( array( 'filters' => array( 'resource' => $resId ), 'cache' => $this->cacheQuery ) );
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
      'url' => array(
        'params' => array( 'label' => __( 'URL' ) ),
        'rules' => array( 'maxlength' => '2000', 'url' => true, 'required' => true )
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
   * Preparamos los datos para visualizar la parte de la extension del formulario
   *
   * @param $form FormController
   *
   * @return Array $viewBlockInfo{ 'template' => array, 'data' => array, 'dataForm' => array }
   */
  // parent::getFormBlockInfo( $form );


  /**
   * Validaciones extra previas a usar los datos
   *
   * @param $form FormController
   */
  // parent::resFormRevalidate( $form );


  /**
   * Creación-Edición-Borrado de los elementos de la extension
   *
   * @param $form FormController
   * @param $resource ResourceModel
   */

  /**
    Validaciones extra previas a usar los datos del recurso base
   */
  public function resFormRevalidate( FormController $form ) {
    // error_log( "RExtUrlController: resFormRevalidate()" );
    $valueEmbed = $form->getFieldValue( $this->addPrefix( 'embed' ) );
    $valueUrl = $form->getFieldValue( $this->addPrefix( 'url' ) );

    if( !$valueUrl && !$valueEmbed ) {
      //$form->addFormError( $msgText, $msgClass = false )
      $form->addFieldRuleError( $this->addPrefix( 'embed' ), 'required' );
      $form->addFieldRuleError( $this->addPrefix( 'url' ), 'required' );
    }
    // $this->evalFormUrlAlias( $form, 'urlAlias' );
  }

  /**
    Creación-Edición-Borrado de los elementos del recurso base
    Iniciar transaction
   */
  public function resFormProcess( FormController $form, ResourceModel $resource ) {
    // error_log( "RExtUrlController: resFormProcess()" );

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
   * Retoques finales antes de enviar el OK-ERROR a la BBDD y al formulario
   *
   * @param $form FormController
   * @param $resource ResourceModel
   */
  // parent::resFormSuccess( $form, $resource )


  /**
    Preparamos los datos para visualizar el Recurso
   */
  public function getViewBlockInfo( $resId = false ) {
    // error_log( "RExtUrlController: getViewBlockInfo( $resId )" );

    $rExtViewBlockInfo = parent::getViewBlockInfo( $resId );

    if( $rExtViewBlockInfo['data'] ) {
      $template = new Template();

      $rExtViewBlockInfo['data']['externalUrl'] = $resId = $this->defResCtrl->resObj->getter('externalUrl');

      $template->assign( 'rExt', array( 'data' => $rExtViewBlockInfo['data'] ) );

      $template->setTpl( 'rExtViewBlock.tpl', 'rextUrl' );

      $rExtViewBlockInfo['template'] = array( 'full' => $template );
    }

    return $rExtViewBlockInfo;
  }

} // class RExtUrlController
