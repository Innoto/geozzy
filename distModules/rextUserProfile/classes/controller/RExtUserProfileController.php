<?php


class RExtUserProfileController extends RExtController implements RExtInterface {

  public $numericFields = false;


  public function __construct( $defRTypeCtrl ){
    // error_log( 'RExtAppUserController::__construct' );

    // $this->numericFields = array( 'averagePrice' );

    parent::__construct( $defRTypeCtrl, new rextUserProfile(), 'rExtUserProfile_' );
  }


  public function getRExtData( $resId = false ) {
    // error_log( "RExtAppUserController: getRExtData( $resId )" );
    $rExtData = false;

    if( $resId === false ) {
      $resId = $this->defResCtrl->resObj->getter('id');
    }

    $rExtModel = new RExtUserProfileModel();
    $rExtList = $rExtModel->listItems( array( 'filters' => array( 'resource' => $resId ) ) );
    $rExtObj = $rExtList->fetch();

    if( $rExtObj ) {
      $rExtData = $rExtObj->getAllData( 'onlydata' );
    }

    // error_log( 'RExtAppUserController: getRExtData = '.print_r( $rExtData, true ) );
    return $rExtData;
  }


  /**
    Defino el formulario
   */
  public function manipulateForm( FormController $form ) {
    // error_log( "RExtAppUserController: manipulateForm()" );

    $rExtFieldNames = array();

    $fieldsInfo = array(
      'user' => array(
        'params' => array( 'type' => 'reserved', 'label' => __( 'User' ) )
      )
    );

    $form->definitionsToForm( $this->prefixArrayKeys( $fieldsInfo ) );

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
    Creación-Edición-Borrado de los elementos del recurso base
    Iniciar transaction
   */
  public function resFormProcess( FormController $form, ResourceModel $resource ) {
    // error_log( "RExtAppUserController: resFormProcess()" );


    if( !$form->existErrors() ) {
      $valuesArray = $this->getRExtFormValues( $form->getValuesArray(), $this->numericFields );
      $valuesArray[ 'resource' ] = $resource->getter( 'id' );

      // error_log( 'NEW RExtAppUserModel: ' . print_r( $valuesArray, true ) );
      $this->rExtModel = new rextUserProfileModel( $valuesArray );
      if( $this->rExtModel === false ) {
        $form->addFormError( 'No se ha podido guardar el recurso. (rExtModel)','formError' );
      }
    }
    if( !$form->existErrors() ) {
      $this->rExtModel->save();
    }

    if( $this->taxonomies ) {
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
    // error_log( "RExtAppUserController: resFormSuccess()" );

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

    $rExtViewBlockInfo = parent::getViewBlockInfo( $resId );

    if( $rExtViewBlockInfo['data'] ) {
      $rExtViewBlockInfo['template']['full'] = new Template();
      $rExtViewBlockInfo['template']['full']->assign( 'rExt', array( 'data' => $rExtViewBlockInfo['data'] ) );
      $rExtViewBlockInfo['template']['full']->setTpl( 'rExtViewBlock.tpl', 'rextUserProfile' );
    }

    return $rExtViewBlockInfo;
  }

} // class RExtAppUserController
