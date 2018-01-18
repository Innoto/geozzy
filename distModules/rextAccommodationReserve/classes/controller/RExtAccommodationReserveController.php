<?php
geozzy::load( 'controller/RExtController.php' );

class RExtAccommodationReserveController extends RExtController implements RExtInterface {

  public $channelOptions = false;

  public function __construct( $defRTypeCtrl ) {
    parent::__construct( $defRTypeCtrl, new rextAccommodationReserve(), 'rExtAccommodationReserve_' );

    rextAccommodationReserve::load( 'controller/channelOptionsConf.php' );
    $this->channelOptions = cogumelo::getSetupValue( 'mod:rextAccommodationReserve:channelOptions' );
  }

  /**
   * Carga los datos de los elementos de la extension
   *
   * @param $resId integer
   *
   * @return array OR false
   */
  public function getRExtData( $resId = false ) {
    $rExtData = false;

    // @todo Esto ten que controlar os idiomas

    if( $resId === false ) {
      $resId = $this->defResCtrl->resObj->getter('id');
    }

    $rExtModel = new RExtAccommodationReserveModel();
    $rExtList = $rExtModel->listItems( array( 'filters' => array( 'resource' => $resId ), 'cache' => $this->cacheQuery ) );

    if( $rExtObj = $rExtList->fetch() ) {
      $rExtData = $rExtObj->getAllData( 'onlydata' );
    }

    return $rExtData;
  }


  /**
   * Defino la parte de la extension del formulario
   *
   * @param $form FormController
   */
  public function manipulateForm( FormController $form ) {

    $rExtFieldNames = array();

    $options = [
      'none' => __( 'None' )
    ];
    foreach( array_keys( $this->channelOptions ) as $value ) {
      $options[ $value ] = $value;
    }

    $fieldsInfo = array(
      'channel' => array(
        'params' => array( 'label' => __( 'Reservations channel' ), 'type' => 'select',
          'options' => $options
        )
      ),
      'idRelate' => array(
        'params' => array( 'label' => __( 'Hotel Identifier' ) ),
        'rules' => array( 'maxlength' => 100 )
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

    /*******************************************************************
     * Importante: Guardar la lista de campos del RExt en 'FieldNames' *
     *******************************************************************/
    //$rExtFieldNames[] = 'FieldNames';
    $form->setField( $this->addPrefix( 'FieldNames' ), array( 'type' => 'reserved', 'value' => $rExtFieldNames ) );

    $form->saveToSession();

    return( $rExtFieldNames );
  }


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
  public function resFormProcess( FormController $form, ResourceModel $resource ) {
    if( !$form->existErrors() ) {
      //$numericFields = array( 'averagePrice', 'capacity' );
      $valuesArray = $this->getRExtFormValues( $form->getValuesArray(), $this->numericFields );

      $valuesArray[ 'resource' ] = $resource->getter( 'id' );

      $rExtModel = new RExtAccommodationReserveModel( $valuesArray );
      if( $rExtModel === false ) {
        $form->addFormError( 'No se ha podido guardar el recurso. (rExtModel)','formError' );
      }
    }

    if( !$form->existErrors() && $this->taxonomies ) {
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
    $rExtViewBlockInfo = parent::getViewBlockInfo( $resId );

    if( $rExtViewBlockInfo['data'] ) {
      $channel = $rExtViewBlockInfo['data']['channel'];
      $channelInfo = cogumelo::getSetupValue( 'mod:rextAccommodationReserve:channelOptions:'.$channel );

      if( $channelInfo ) {
        $rExtViewBlockInfo['template']['full'] = new Template();

        $rExtViewBlockInfo['template']['full']->assign( 'rExt', array( 'data' => $rExtViewBlockInfo['data'] ) );
        $rExtViewBlockInfo['template']['full']->assign( 'channelInfo', $channelInfo );
        $rExtViewBlockInfo['template']['full']->assign( 'calDateFormat', 'DD/MM/YYYY' );

        $rExtViewBlockInfo['template']['full']->setTpl( $channelInfo['template']['public'], 'rextAccommodationReserve' );

        if( isset( $channelInfo['template']['scripts'] ) ) {
          foreach( $channelInfo['template']['scripts'] as $script ) {
            $rExtViewBlockInfo['template']['full']->addClientScript( $script, 'rextAccommodationReserve' );
          }
        }
        if( isset( $channelInfo['template']['styles'] ) ) {
          foreach( $channelInfo['template']['styles'] as $style ) {
            $rExtViewBlockInfo['template']['full']->addClientStyles( $style, 'rextAccommodationReserve' );
          }
        }

        $from = [
          '<$idRelate>',
          '<$langName>'
        ];
        $to = [
          $rExtViewBlockInfo['data']['idRelate'],
          $GLOBALS['C_LANG']
        ];
        $srcUrl = str_replace( $from, $to, $channelInfo['pattern'] );
        $rExtViewBlockInfo['template']['full']->assign( 'srcUrl', $srcUrl );
      }
    }

    // Template por defecto
    if( !isset( $rExtViewBlockInfo['template']['full'] ) ) {
      $rExtViewBlockInfo['template']['full'] = new Template();
      $rExtViewBlockInfo['template']['full']->assign( 'rExt', array( 'data' => $rExtViewBlockInfo['data'] ) );
      $rExtViewBlockInfo['template']['full']->setTpl( 'rExtViewBlock.tpl', 'rextAccommodationReserve' );
    }

    return $rExtViewBlockInfo;
  }

} // class RExtAccommodationReserveController
