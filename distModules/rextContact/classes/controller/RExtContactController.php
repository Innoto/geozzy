<?php


class RExtContactController extends RExtController implements RExtInterface {

  public $numericFields = false;


  public function __construct( $defRTypeCtrl ){
    error_log( 'RExtContactController::__construct' );

    parent::__construct( $defRTypeCtrl, new rextContact(), 'rExtContact_' );
  }


  public function getRExtData( $resId = false ) {
    // error_log( "RExtContactController: getRExtData( $resId )" );
    $rExtData = false;

    if( $resId === false ) {
      $resId = $this->defResCtrl->resObj->getter('id');
    }

    $rExtModel = new ContactModel();
    $rExtList = $rExtModel->listItems( array( 'filters' => array( 'resource' => $resId ) ) );
    $rExtObj = $rExtList->fetch();

    if( $rExtObj ) {
      $rExtData = $rExtObj->getAllData( 'onlydata' );
    }


    error_log( 'RExtContactController: getRExtData = '.print_r( $rExtData, true ) );
    return $rExtData;
  }


  /**
    Defino el formulario
   */
  public function manipulateForm( FormController $form ) {
    // error_log( "RExtContactController: manipulateForm()" );

    $rExtFieldNames = array();

    $fieldsInfo = array(
      'address' => array(
        'params' => array( 'label' => __( 'Address' ) ),
        'rules' => array( 'maxlength' => 200 )
      ),
      'city' => array(
        'params' => array( 'label' => __( 'City' ) ),
        'rules' => array( 'maxlength' => 60 )
      ),
      'cp' => array(
        'params' => array( 'label' => __( 'Postal code' ) ),
        'rules' => array( 'maxlength' => 8 )
      ),
      'province' => array(
        'params' => array( 'label' => __( 'Province' ) ),
        'rules' => array( 'maxlength' => 60 )
      ),
      'phone' => array(
        'params' => array( 'label' => __( 'Phone' ) ),
        'rules' => array( 'maxlength' => 20 )
      ),
      'email' => array(
        'params' => array( 'label' => __( 'Contact email' ) ),
        'rules' => array( 'maxlength' => 255, 'email' => true)
      ),
      'directions' => array(
        'translate' => true,
        'params' => array( 'label' => __( 'How to arrive' ), 'type' => 'textarea' ),
        'rules' => array( 'maxlength' => 2000 )
      ),
      'timetable' => array(
        'translate' => true,
        'params' => array( 'label' => __( 'Opening times' ), 'type' => 'textarea' ),
        'rules' => array( 'maxlength' => 2000 )
      ),
      'url' => array(
        'params' => array( 'label' => __( 'Contact url' ) ),
        'rules' => array( 'maxlength' => 2000 )
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
    error_log( "RExtContactController: getFormBlockInfo()" );

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
    $templates['full']->setTpl( 'rExtFormBlock.tpl', 'rextContact' );
    $templates['full']->assign( 'rExt', $formBlockInfo );

    $formBlockInfo['template'] = $templates;

    return $formBlockInfo;
  }







  /**
    Validaciones extra previas a usar los datos del recurso base
   */
  public function resFormRevalidate( FormController $form ) {
    // error_log( "RExtContactController: resFormRevalidate()" );

    // $this->evalFormUrlAlias( $form, 'urlAlias' );
  }

  /**
    Creación-Edición-Borrado de los elementos del recurso base
    Iniciar transaction
   */
  public function resFormProcess( FormController $form, ResourceModel $resource ) {
    // error_log( "RExtContactController: resFormProcess()" );

    if( !$form->existErrors() ) {
      $valuesArray = $this->getRExtFormValues( $form->getValuesArray(), $this->numericFields );

      $valuesArray[ 'resource' ] = $resource->getter( 'id' );

      // error_log( 'NEW rExtContact: ' . print_r( $valuesArray, true ) );
      $rExtModel = new ContactModel( $valuesArray );
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
    // error_log( "RExtContactController: resFormSuccess()" );

  }



  /**
    Visualizamos el Recurso (extensión Contact)
   */
  public function getViewBlock( Template $resBlock ) {
    error_log( "RExtContactController: getViewBlock()" );
    $template = false;

    $resId = $this->defResCtrl->resObj->getter('id');
    $rExtData = $this->getRExtData( $resId );

    $rExtData['web'] = 'Hai que quitar esto e collese a orl do modelo';

    if( $rExtData ) {
      $template = new Template();
      $rExtData = $this->prefixArrayKeys( $rExtData );
      $template->assign( 'rExtContact_timetable', $rExtData['rExtContact_timetable_'.LANG_DEFAULT] );
      foreach( $rExtData as $key => $value ) {
        $template->assign( $key, ($value) ? $value : '' );
        // error_log( $key . ' === ' . print_r( $value, true ) );
      }

      $template->assign( 'rExtFieldNames', array_keys( $rExtData ) );
      $template->setTpl( 'rExtViewBlock.tpl', 'rextContact' );
    }

    return $template;
  }



  /**
    Datos y template por defecto de la extension
   */
  public function getViewBlockInfo() {
    error_log( "RExtContactController: getViewBlockInfo()" );

    $rExtViewBlockInfo = array(
      'template' => false,
      'data' => $this->getRExtData()
    );

    if( $rExtViewBlockInfo['data'] ) {
      // TODO: esto será un campo da BBDD
      $rExtViewBlockInfo['data'] = $this->defResCtrl->getTranslatedData( $rExtViewBlockInfo['data'] );


      $template = new Template();

      $template->assign( 'rExt', array( 'data' => $rExtViewBlockInfo['data'] ) );

      $template->setTpl( 'rExtViewBlock.tpl', 'rextContact' );

      $rExtViewBlockInfo['template'] = array( 'full' => $template );
    }

    return $rExtViewBlockInfo;
  }

} // class RExtContactController
