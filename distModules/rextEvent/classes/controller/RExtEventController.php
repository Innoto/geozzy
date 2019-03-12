<?php
geozzy::load( 'controller/RExtController.php' );

class RExtEventController extends RExtController implements RExtInterface {

  public $selectOptions = [];

  public function __construct( $defRTypeCtrl = null ){
    parent::__construct( $defRTypeCtrl, new rextEvent(), 'rextEvent_' );

    $this->selectOptions = [
      'selectTime' => [
        'notTime' => __('No time'),
        'notRangeTime' => __('Without time range'),
        'rangeTime' => __('Time range')
      ]
    ];
  }


  /**
   * Carga los datos de los elementos de la extension
   *
   * @param $resId integer
   *
   * @return array OR false
   */
  public function getRExtData( $resId = false ) {
    // error_log( "RExtEventController: getRExtData( $resId )" );
    $rExtData = false;

    if( $resId === false ) {
      $resId = $this->defResCtrl->resObj->getter('id');
    }

    $rExtModel = new EventModel();
    $rExtList = $rExtModel->listItems( array( 'filters' => array( 'resource' => $resId ), 'cache' => $this->cacheQuery ) );
    $rExtObj = $rExtList->fetch();

    if( $rExtObj ) {
      // $rExtData = $rExtObj->getAllData( 'onlydata' );
      $rExtData = $this->defResCtrl->getAllTrData( $rExtObj );


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

    // error_log( 'RExtEventController: getRExtData = '.print_r( $rExtData, true ) );
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
      'eventTitle' => array(
        'translate' => true,
        'params' => array( 'label' => __( 'Literal Date' ) ),
        'rules' => array( 'maxlength' => 50 )
      ),

      'selectInitTime' => array(
        'params' => array( 'label' => __('Select time'), 'type' => 'select',
          'class' => 'gzzSelect2',
          'options' => $this->selectOptions['selectTime'], 'value' => 'notTime'
        )
      ),
      'initDateFirst' => array(
        'params' => array( 'type' => 'hidden' ),
        'rules' => array( 'maxlength' => 200 )
      ),
      'initDateSecond' => array(
        'params' => array( 'type' => 'hidden' ),
        'rules' => array( 'maxlength' => 200 )
      ),

      'dateRange' => array(
        'params' => array( 'type' => 'checkbox', 'class' => 'switchery', 'options'=> [ '1' => __('Final date of event') ] )
      ),

      'selectEndTime' => array(
        'params' => array( 'label' => __('Select time'), 'type' => 'select',
          'class' => 'gzzSelect2',
          'options' => $this->selectOptions['selectTime'], 'value' => 'notTime'
        )
      ),
      'endDateFirst' => array(
        'params' => array( 'type' => 'hidden' ),
        'rules' => array( 'maxlength' => 200 )
      ),
      'endDateSecond' => array(
        'params' => array( 'type' => 'hidden' ),
        'rules' => array( 'maxlength' => 200 )
      ),

      'rextEventType' => array(
        'params' => array( 'label' => __( 'Event type' ), 'type' => 'select',  'multiple' => true, 'class' => 'cgmMForm-order',
          'options' => $this->defResCtrl->getOptionsTax( 'rextEventType' )
        )
      ),
      'relatedResource' => array(
        'params' => array( 'label' => __( 'Related resource' ), 'type' => 'select',
          'options' => $allRes
        )
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

    $formBlockInfo = parent::getFormBlockInfo( $form );
    $templates = $formBlockInfo['template'];

    $templates['full'] = new Template();
    $templates['full']->setTpl( 'rExtFormBlock.tpl', 'rextEvent' );
    $templates['full']->assign( 'rExtName', $this->rExtName );
    $templates['full']->assign( 'rExt', $formBlockInfo );
    $templates['full']->addClientScript('js/rextEvent.js', 'rextEvent');

    $prevContent = '<style type="text/css">.bootstrap-datetimepicker-widget table th{background: none;}</style>';
    $templates['full']->assign( 'prevContent', $prevContent );

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
    // error_log( "RExtEventController: resFormProcess()" );
    if( !$form->existErrors() ) {

      //$numericFields = array( 'averagePrice', 'capacity' );
      $valuesArray = $this->getRExtFormValues( $form->getValuesArray(), $this->numericFields );
      $valuesArray[ 'resource' ] = $resource->getter( 'id' );

      $initDateFirst = date_create( $valuesArray['initDateFirst'] );
      if( !empty( $initDateFirst ) && !empty( $valuesArray['initDateFirst'] ) ) {
        $valuesArray[ 'initDateFirst' ] = date_format( $initDateFirst, "Y-m-d H:i:s" );
      }
      else {
        if( strpos($valuesArray[ 'initDateFirst' ],'-') ) {
          unset($valuesArray[ 'initDateFirst' ]);
        }
        else {
          $valuesArray[ 'initDateFirst' ] = null;
        }
      }

      $initDateSecond = date_create( $valuesArray['initDateSecond'] );
      if( !empty( $initDateSecond ) && !empty( $valuesArray['initDateSecond'] ) ) {
        $valuesArray[ 'initDateSecond' ] = date_format( $initDateSecond, "Y-m-d H:i:s" );
      }
      else {
        if( strpos($valuesArray[ 'initDateSecond' ],'-') ) {
          unset($valuesArray[ 'initDateSecond' ]);
        }
        else {
          $valuesArray[ 'initDateSecond' ] = null;
        }
      }

      if( $valuesArray[ 'dateRange' ] ) {
        $endDateFirst = date_create( $valuesArray['endDateFirst'] );
        if( !empty( $endDateFirst ) && !empty( $valuesArray['endDateFirst'] ) ) {
          $valuesArray[ 'endDateFirst' ] = date_format( $endDateFirst, "Y-m-d H:i:s" );
        }
        else {
          if( strpos($valuesArray[ 'endDateFirst' ],'-') ) {
            unset($valuesArray[ 'endDateFirst' ]);
          }
          else {
            $valuesArray[ 'endDateFirst' ] = null;
          }
        }

        $endDateSecond = date_create( $valuesArray['endDateSecond'] );
        if( !empty( $endDateSecond ) && !empty( $valuesArray['endDateSecond'] ) ) {
          $valuesArray[ 'endDateSecond' ] = date_format( $endDateSecond, "Y-m-d H:i:s" );
        }
        else {
          if( strpos($valuesArray[ 'endDateSecond' ],'-') ) {
            unset($valuesArray[ 'endDateSecond' ]);
          }
          else {
            $valuesArray[ 'endDateSecond' ] = null;
          }
        }
      }
      else{
        $valuesArray[ 'endDateFirst' ] = null;
        $valuesArray[ 'endDateSecond' ] = null;
      }

      // error_log( 'NEW RESOURCE: ' . print_r( $valuesArray, true ) );
      $rExtModel = new EventModel( $valuesArray );
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
   * Preparamos los datos para visualizar la parte de la extension
   *
   * @return Array $rExtViewBlockInfo{ 'template' => array, 'data' => array }
   */
  public function getViewBlockInfo( $resId = false ) {
    //TODO: Falta actualizar método a nueva forma de trabajar con abstracción -> pendente decidir visualización de eventos / pois

    $rExtViewBlockInfo = parent::getViewBlockInfo( $resId );

    if( !empty( $rExtViewBlockInfo['data'] ) ) {
      $rExtViewBlockInfo['data'] = $this->defResCtrl->getTranslatedData( $rExtViewBlockInfo['data'] );

      $rExtViewBlockInfo['data']['eventText'] = $this->getEventTextFormated( $rExtViewBlockInfo['data'] );


      $rExtViewBlockInfo['template']['full'] = new Template();
      $rExtViewBlockInfo['template']['full']->assign( 'rExt', array( 'data' => $rExtViewBlockInfo['data'] ) );
      $rExtViewBlockInfo['template']['full']->setTpl( 'rExtViewBlock.tpl', 'rextEvent' );
    }

    return $rExtViewBlockInfo;
  }


  /* Function to get text formated of one event */
  public function getEventTextFormated( $eventDataArray ) {

    /*
    // VALORES QUE NECESITAMOS PARA OBTENER DATOS (return $eventText;)
      $nameArray = [
        'eventTitle' => $eventObj->getter('eventTitle',false),
        'selectInitTime' => $eventObj->getter('selectInitTime'),
        'initDateFirst' => $eventObj->getter('initDateFirst'),
        'initDateSecond' => $eventObj->getter('initDateSecond'),
        'selectEndTime' => $eventObj->getter('selectEndTime'),
        'endDateFirst' => $eventObj->getter('endDateFirst'),
        'endDateSecond' => $eventObj->getter('endDateSecond'),
        'dateRange' => $eventObj->getter('dateRange')
      ];
    */

    $eventText = '';
    $formatDate = 'Y/m/d';
    global $C_LANG;
    if( $C_LANG === 'es' || $C_LANG === 'gl' ) {
      $formatDate = 'd/m/Y';
    }
    // Conexiones
    $fromText = __('from');
    $toText = __('to');
    $atText = __('\a\t'); //  Al estar dentro de un date_format necesita estar escapado para no provocar problemas de formateo (en po's también)

    if( !empty( $eventDataArray['eventTitle'] ) ) {
      $eventText = $eventDataArray['eventTitle'];
    }
    else {
      if( !empty( $eventDataArray['initDateFirst'] ) ) {
        $dateEvent = date_create( $eventDataArray['initDateFirst'] );
        if( $eventDataArray['selectInitTime'] === 'notTime' ) {
          $eventText = date_format( $dateEvent, $formatDate );
        }
        else if( $eventDataArray['selectInitTime'] === 'notRangeTime' ) {
          $eventText = date_format( $dateEvent, $formatDate.' '.$atText.' '.'H:i' ).'h';
        }
        else if( $eventDataArray['selectInitTime'] === 'rangeTime' ) {
          $eventText = date_format( $dateEvent, $formatDate.' (H:i' ).'h';
          if( !empty( $eventDataArray['initDateSecond'] ) ) {
            $dateEventSecond = date_create( $eventDataArray['initDateSecond'] );
            $eventText .= ' '.$toText.' '.date_format( $dateEventSecond, 'H:i' ).'h';
          }
          $eventText .= ')';
        }
      }

      if( $eventDataArray['dateRange'] ) {
        // Existen 2 fechas (initDate/endDate)
        if( !empty( $eventDataArray['endDateFirst'] ) ) {
          $eventText = $fromText.' '.$eventText;
          $dateEvent = date_create( $eventDataArray['endDateFirst'] );
          if( $eventDataArray['selectEndTime'] === 'notTime' ) {
            $eventText .= ' '.$toText.' '.date_format( $dateEvent, $formatDate );
          }
          else if( $eventDataArray['selectEndTime'] === 'notRangeTime' ) {
            $eventText .= ' '.$toText.' '.date_format( $dateEvent, $formatDate.' '.$atText.' '.'H:i' ).'h';
          }
          else if( $eventDataArray['selectEndTime'] === 'rangeTime' ) {
            $eventText .= ' '.$toText.' '.date_format( $dateEvent, $formatDate.' (H:i' ).'h';
            if( !empty( $eventDataArray['endDateSecond'] ) ) {
              $dateEventSecond = date_create( $eventDataArray['endDateSecond'] );
              $eventText .= ' '.$toText.' '.date_format( $dateEventSecond, 'H:i' ).'h';
            }
            $eventText .= ')';
          }
        }
      }
    }

    return $eventText;
  }

} // class RExtEventController
