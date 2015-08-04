<?php


class RExtAccommodationController extends RExtController implements RExtInterface {


  public function __construct( $defRTypeCtrl ){
    error_log( 'RExtAccommodationController::__construct' );

    parent::__construct( $defRTypeCtrl, new rextAccommodation(), 'rExtAccommodation_' );
  }


  public function getRExtData( $resId ) {
    error_log( "ResourceController: getRExtData( $resId )" );
    $rExtData = false;

    $rExtModel = new AccommodationModel();
    $rExtList = $rExtModel->listItems( array( 'filters' => array( 'resource' => $resId ) ) );
    $rExtObj = $rExtList->fetch();

    if( $rExtObj ) {
      $rExtData = $rExtObj->getAllData( 'onlydata' );

      // Cargo los datos de destacados con los que está asociado el recurso
      $taxTerms = $this->defResCtrl->getResTerms( $resId );
      if( $taxTerms ) {
        foreach( $this->taxonomies as $tax ) {
          // TODO: Separar los terms por taxonomia
          $rExtData[ $tax['idName'] ] = $taxTerms;
        }
      }

    }

    // error_log( 'ResourceController: getRExtData = '.print_r( $rExtData, true ) );
    return $rExtData;
  }


  /**
    Defino el formulario
   */
  public function manipulateForm( FormController $form ) {
    error_log( "RExtAccommodationController: manipulateForm()" );

    $rExtFieldNames = array();

    $fieldsInfo = array(
      'reservationURL' => array(
        'params' => array( 'label' => __( 'Hotel reservation URL' ) ),
        'rules' => array( 'maxlength' => 2000 )
      ),
      'reservationPhone' => array(
        'params' => array( 'label' => __( 'Hotel reservation phone' ) ),
        'rules' => array( 'maxlength' => 200 )
      ),
      'singleRooms' => array(
        'params' => array( 'label' => __( 'Hotel single rooms' ) ),
        'rules' => array( 'digits' => true )
      ),
      'doubleRooms' => array(
        'params' => array( 'label' => __( 'Hotel double rooms' ) ),
        'rules' => array( 'digits' => true )
      ),
      'familyRooms' => array(
        'params' => array( 'label' => __( 'Hotel family rooms' ) ),
        'rules' => array( 'digits' => true )
      ),
      'beds' => array(
        'params' => array( 'label' => __( 'Hotel beds' ) ),
        'rules' => array( 'digits' => true )
      ),
      'averagePrice' => array(
        'params' => array( 'label' => __( 'Hotel average price' ) ),
        'rules' => array( 'digits' => true )
      ),
      'accommodationType' => array(
        'params' => array( 'label' => __( 'Accommodation type' ), 'type' => 'select',
          'options' => $this->defResCtrl->getOptionsTax( 'accommodationType' )
        )
      ),
      'accommodationCategory' => array(
        'params' => array( 'label' => __( 'Accommodation category' ), 'type' => 'select',
          'options' => $this->defResCtrl->getOptionsTax( 'accommodationCategory' )
        )
      ),
      'accommodationServices' => array(
        'params' => array( 'label' => __( 'Accommodation services' ), 'type' => 'select',
          'options' => $this->defResCtrl->getOptionsTax( 'accommodationServices' )
        )
      ),
      'accommodationFacilities' => array(
        'params' => array( 'label' => __( 'Accommodation facilities' ), 'type' => 'select',
          'options' => $this->defResCtrl->getOptionsTax( 'accommodationFacilities' )
        )
      ),
      'accommodationBrand' => array(
        'params' => array( 'label' => __( 'Accommodation brand' ), 'type' => 'select',
          'options' => $this->defResCtrl->getOptionsTax( 'accommodationBrand' )
        )
      ),
      'accommodationUsers' => array(
        'params' => array( 'label' => __( 'Accommodation users profile' ), 'type' => 'select',
          'options' => $this->defResCtrl->getOptionsTax( 'accommodationUsers' )
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

    $form->setField( 'rExtAccommodationFieldNames', array( 'type' => 'reserved', 'value' => $rExtFieldNames ) );
    $form->saveToSession();

    return( $rExtFieldNames );
  } // function manipulateForm()



  /**
    Validaciones extra previas a usar los datos del recurso base
   */
  public function resFormRevalidate( FormController $form ) {
    error_log( "RExtAccommodationController: resFormRevalidate()" );

    // $this->evalFormUrlAlias( $form, 'urlAlias' );
  }

  /**
    Creación-Edición-Borrado de los elementos del recurso base
    Iniciar transaction
   */
  public function resFormProcess( FormController $form, ResourceModel $resource ) {
    error_log( "RExtAccommodationController: resFormProcess()" );

    if( !$form->existErrors() ) {
      $numericFields = array( 'singleRooms', 'doubleRooms', 'familyRooms', 'beds', 'averagePrice' );
      $valuesArray = $this->getRExtFormValues( $form->getValuesArray(), $numericFields );

      $valuesArray[ 'resource' ] = $resource->getter( 'id' );

      error_log( 'NEW rExtAccommodation: ' . print_r( $valuesArray, true ) );
      $rExtModel = new AccommodationModel( $valuesArray );
      if( $rExtModel === false ) {
        $form->addFormError( 'No se ha podido guardar el recurso. (rExtModel)','formError' );
      }
    }

    foreach( $this->taxonomies as $tax ) {
      $taxFieldName = $this->addPrefix( $tax[ 'idName' ] );
      if( !$form->existErrors() && $form->isFieldDefined( $taxFieldName ) ) {
        $this->defResCtrl->setFormTax( $form, $taxFieldName, $tax[ 'idName' ], $form->getFieldValue( $taxFieldName ), $resource );
      }
    }

    if( !$form->existErrors()) {
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
    error_log( "RExtAccommodationController: resFormSuccess()" );

  }


  /**
    Visualizamos el Recurso
  */
  public function getViewBlock( ResourceModel $resource, Template $resBlock ) {
    error_log( "RExtAccommodationController: getViewBlock()" );
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
      $template->setTpl( 'rExtViewBlock.tpl', 'rextAccommodation' );
    }

    return $template;
  }

} // class RExtAccommodationController
