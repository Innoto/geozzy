<?php
geozzy::load( 'controller/RExtController.php' );
geozzy::load( 'controller/RTypeController.php' );

class RExtPoiController extends RExtController implements RExtInterface {

  public $numericFields = false;


  public function __construct( $defRTypeCtrl ){
    parent::__construct( $defRTypeCtrl, new rextPoi(), 'rextPoi_' );
  }


  public function getRExtData( $resId = false ) {
    // error_log( "RExtPoiController: getRExtData( $resId )" );
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
    return $rExtData;
  }


  /**
   * Defino la parte de la extension del formulario
   *
   * @param $form FormController
   */
  public function manipulateForm( FormController $form ) {
    // error_log( "RExtContactController: manipulateForm()" );
    $rExtFieldNames = array();

    // systemRTypes
    $systemRtypes = Cogumelo::getSetupValue('mod:geozzy:resource:systemRTypes');

    $resourceModel = new ResourceModel();
    $rtypeModel = new resourceTypeModel();

    $rtypeArray = $rtypeModel->listItems(
        array( 'filters' => array( 'idNameExists' => $systemRtypes ), 'cache' => $this->cacheQuery )
    );
    $filterRtype = array();
    while( $rtype = $rtypeArray->fetch() ){
      array_push( $filterRtype, $rtype->getter('id') );
    }

    $elemList = $resourceModel->listItems(
      array( 'filters' => array( 'notInRtype' => $filterRtype ), 'cache' => $this->cacheQuery )
    );
    $allRes = array();
    $allRes['0'] = false;
    while( $elem = $elemList->fetch() ){
      $allRes[ $elem->getter( 'id' ) ] = $elem->getter( 'title' );
    }

    $fieldsInfo = array(
      'rextPoiType' => array(
        'params' => array( 'label' => __( 'POI type' ), 'type' => 'select',  'multiple' => true, 'class' => 'cgmMForm-order',
          'options' => $this->defResCtrl->getOptionsTax( 'rextPoiType' )
        )
      ),
      'rextPoiPitch' => array(
        'params' => array( 'label' => __( 'Pitch (Vertical)' ) ),
        'rules' => array( 'numberEUDec' => 4 )
      ),
      'rextPoiYaw' => array(
        'params' => array( 'label' => __( 'Yaw (Horizontal)' ) ),
        'rules' => array( 'numberEUDec' => 4 )
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

    /*******************************************************************
     * Importante: Guardar la lista de campos del RExt en 'FieldNames' *
     *******************************************************************/
    //$rExtFieldNames[] = 'FieldNames';
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
  public function getFormBlockInfo( FormController $form ) {

    $resData = $form->getHtmlFieldsArray( array('locLat', 'locLon', 'defaultZoom') );

    $formBlockInfo = parent::getFormBlockInfo( $form );
    $templates = $formBlockInfo['template'];

    $templates['adminExt'] = new Template();
    $templates['adminExt']->setTpl( 'rExtViewBlock.tpl', 'rextPoi' );
    $templates['adminExt']->assign( 'rExtName', $this->rExtName );
    $templates['adminExt']->assign( 'rExt', $formBlockInfo );
    $templates['adminExt']->assign( 'res', $resData );
    $templates['adminExt']->addClientScript('js/rextPoi.js', 'rextPoi');


    $formBlockInfo['template'] = $templates;

    return $formBlockInfo;
  }



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
  public function resFormProcess( FormController $form, ResourceModel $resource ) {
    // error_log( "RExtPoiController: resFormProcess()" );
    if( !$form->existErrors() ) {
      //$numericFields = array( 'averagePrice', 'capacity' );
      $valuesArray = $this->getRExtFormValues( $form->getValuesArray(), $this->numericFields );
      $valuesArray[ 'resource' ] = $resource->getter( 'id' );
    }

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
   * Retoques finales antes de enviar el OK-ERROR a la BBDD y al formulario
   *
   * @param $form FormController
   * @param $resource ResourceModel
   */
  // parent::resFormSuccess( $form, $resource )


  /**
   * Preparamos los datos para visualizar la parte de la extension
   *
   * @return Array $rExtViewBlockInfo{ 'template' => array, 'data' => array }
   */
  public function getViewBlockInfo( $resId = false ) {
    $rExtViewBlockInfo = parent::getViewBlockInfo( $resId );

    if( $rExtViewBlockInfo['data'] ) {

      $rExtViewBlockInfo['data'] = $this->defResCtrl->getTranslatedData( $rExtViewBlockInfo['data'] );

      if (isset($rExtViewBlockInfo['data'])){
        $template = new Template();
        $template->assign( 'rExt', array( 'data' => $rExtViewBlockInfo['data'] ) );
        $rExtViewBlockInfo['template'] = array( 'full' => $template );
      }
    }

    return $rExtViewBlockInfo;
  }

} // class RExtPoiController
